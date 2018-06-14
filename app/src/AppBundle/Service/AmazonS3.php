<?php

namespace AppBundle\Service;

use Aws\S3\S3Client;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class AmazonS3
 * @package AppBundle\Service
 */
class AmazonS3
{
    /**
     * @var S3Client
     */
    private $client;
    /**
     * @var string
     */
    private $bucket;
    /**
     * @param string $bucket
     * @param array  $s3arguments
     */
    public function __construct($bucket, array $s3arguments)
    {
        $this->setBucket($bucket);
        $this->setClient(new S3Client($s3arguments));
    }
    /**
     * @param string $fileName
     * @param string $content
     * @param array  $meta
     * @param string $privacy
     * @return string file url
     */
    public function upload( $fileName, $content, array $meta = [], $privacy = 'public-read')
    {
        return $this->getClient()->upload($this->getBucket(), $fileName, $content, $privacy, [
            'Metadata' => $meta,
        ])->toArray()['ObjectURL'];
    }
    /**
     * @param string       $fileName
     * @param string|null  $newFilename
     * @param array        $meta
     * @param string       $privacy
     * @return string file url
     */
    public function uploadFile($fileName, $newFilename = null, array $meta = [], $privacy = 'public-read') {
        if(!$newFilename) {
            $newFilename = basename($fileName);
        }
        if(!isset($meta['contentType'])) {
            // Detect Mime Type
            $mimeTypeHandler = finfo_open(FILEINFO_MIME_TYPE);
            $meta['contentType'] = finfo_file($mimeTypeHandler, $fileName);
            finfo_close($mimeTypeHandler);
        }
        return $this->upload($newFilename, file_get_contents($fileName), $meta, $privacy);
    }
    /**
     * Getter of client
     *
     * @return S3Client
     */
    protected function getClient()
    {
        return $this->client;
    }
    /**
     * Setter of client
     *
     * @param S3Client $client
     *
     * @return $this
     */
    private function setClient(S3Client $client)
    {
        $this->client = $client;
        return $this;
    }
    /**
     * Getter of bucket
     *
     * @return string
     */
    protected function getBucket()
    {
        return $this->bucket;
    }
    /**
     * Setter of bucket
     *
     * @param string $bucket
     *
     * @return $this
     */
    private function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * @param string $from
     * @param string $to
     * @param array $list
     * @return string|null
     */
    public function getUrl(string $from, string $to, array $list): ?string
    {
        $fileSystem = new Filesystem();
        $tmpFile = '/tmp/s3.csv';
        $handle = fopen($tmpFile, 'w+');
        // insert header
        fputcsv($handle, [
            'sku',
            'name',
            'price_eur',
            'created_at',
        ], ';');
        /** @var \AppBundle\Entity\Products $product */
        foreach ($list as $product) {
            // insert rows
            fputcsv($handle, [
                $product->getSku(),
                $product->getName(),
                // TODO: get price
                '',
                $product->getCreatedAt()->format('Y-m-d'),
            ], ';');
        }
        fclose($handle);

        $url =  $this->uploadFile(
            $tmpFile,
            sprintf( 'products_%s_%s.csv', $from, $to)
        );
        $fileSystem->remove($tmpFile);

        return $url ?? null;
    }
}
