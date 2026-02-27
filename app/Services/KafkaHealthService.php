<?php

namespace App\Services;

use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer;
use RdKafka\TopicPartition;

class KafkaHealthService
{
    private string $broker;
    private string $topic;
    private string $consumerGroup;

    public function __construct()
    {
        $this->broker        = env('KAFKA_BROKER_LIST', '127.0.0.1:9092');
        $this->topic         = env('KAFKA_QUEUE', 'logs');
        // Pakai group yang SAMA dengan yang dipakai queue worker
        $this->consumerGroup = env('KAFKA_GROUP_ID', 'laravel-queue');
    }

    /**
     * Cek apakah bisa terhubung ke Kafka broker.
     */
    public function isConnected(): bool
    {
        try {
            $conf = new Conf();
            $conf->set('bootstrap.servers', $this->broker);
            $conf->set('socket.timeout.ms', '2000');

            $producer = new Producer($conf);
            $metadata = $producer->getMetadata(true, null, 2000);

            return $metadata !== null;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Ambil daftar broker yang aktif.
     */
    public function getBrokers(): array
    {
        try {
            $conf = new Conf();
            $conf->set('bootstrap.servers', $this->broker);
            $conf->set('socket.timeout.ms', '2000');

            $producer  = new Producer($conf);
            $metadata  = $producer->getMetadata(true, null, 2000);
            $brokers   = [];

            foreach ($metadata->getBrokers() as $b) {
                $brokers[] = $b->getHost() . ':' . $b->getPort();
            }

            return $brokers;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Ambil jumlah total partition dari topic "logs".
     */
    public function getTopicPartitionCount(): int
    {
        try {
            $conf = new Conf();
            $conf->set('bootstrap.servers', $this->broker);
            $conf->set('socket.timeout.ms', '2000');

            $producer   = new Producer($conf);
            $topic      = $producer->newTopic($this->topic);
            $metadata   = $producer->getMetadata(false, $topic, 2000);
            $topics     = $metadata->getTopics();

            foreach ($topics as $t) {
                if ($t->getTopic() === $this->topic) {
                    return count($t->getPartitions());
                }
            }

            return 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Hitung LAG total (pesan yang belum diproses worker).
     * LAG = (latest offset - committed offset) per partition.
     */
    public function getTotalLag(): int
    {
        try {
            $conf = new Conf();
            $conf->set('bootstrap.servers', $this->broker);
            $conf->set('group.id', $this->consumerGroup);
            $conf->set('socket.timeout.ms', '3000');
            $conf->set('enable.auto.commit', 'false');

            $consumer   = new KafkaConsumer($conf);
            $metadata   = $consumer->getMetadata(false, $consumer->newTopic($this->topic), 3000);
            $totalLag   = 0;

            foreach ($metadata->getTopics() as $topicMeta) {
                if ($topicMeta->getTopic() !== $this->topic) continue;

                foreach ($topicMeta->getPartitions() as $partition) {
                    $pid = $partition->getId();

                    // Latest offset (high watermark)
                    [$lowOffset, $highOffset] = [RD_KAFKA_OFFSET_BEGINNING, RD_KAFKA_OFFSET_END];
                    $consumer->queryWatermarkOffsets($this->topic, $pid, $lowOffset, $highOffset, 2000);

                    // Committed offset untuk consumer group
                    $topicPartitions = [new TopicPartition($this->topic, $pid)];
                    $committed = $consumer->getCommittedOffsets($topicPartitions, 2000);

                    $committedOffset = $committed[0]->getOffset();

                    // Kalau belum pernah commit (nilai negatif), LAG = seluruh message
                    if ($committedOffset < 0) {
                        $totalLag += $highOffset;
                    } else {
                        $totalLag += max(0, $highOffset - $committedOffset);
                    }
                }
            }

            $consumer->close();

            return $totalLag;
        } catch (\Throwable $e) {
            return -1; // -1 = gagal ambil data
        }
    }

    /**
     * Ambil semua info sekaligus (untuk ditampilkan di dashboard).
     */
    public function getStatus(): array
    {
        $connected  = $this->isConnected();
        $brokers    = $connected ? $this->getBrokers() : [];
        $partitions = $connected ? $this->getTopicPartitionCount() : 0;
        $lag        = $connected ? $this->getTotalLag() : -1;

        /**
         * Off-by-one correction:
         * Kafka committed offset selalu 1 di belakang high watermark per partition.
         * LAG <= jumlah partition artinya SEMUA pesan sudah diproses (effective LAG = 0).
         */
        $effectiveLag = ($lag >= 0 && $partitions > 0 && $lag <= $partitions) ? 0 : $lag;

        return [
            'connected'      => $connected,
            'status'         => $connected ? 'UP' : 'DOWN',
            'brokers'        => $brokers,
            'topic'          => $this->topic,
            'partitions'     => $partitions,
            'lag'            => $effectiveLag,
            'lag_raw'        => $lag,
            'lag_label'      => $effectiveLag === -1 ? 'N/A' : ($effectiveLag === 0 ? '0 (semua terproses)' : "{$effectiveLag} pesan menunggu"),
            'consumer_group' => $this->consumerGroup,
            'checked_at'     => now()->format('H:i:s'),
        ];
    }
}
