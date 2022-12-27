<?php

class S3Wrapper
{
    private $s3;
    public function __construct($endpoint, $credentials, $region)
    {
        $this->s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            // randomly chosen, apparently any arbitrary value works
            'region' => $region,
            'endpoint' => $endpoint,
            'use_path_style_endpoint' => true,
            // if they were actual AWS credentials, they must not be hardcoded here
            'credentials' => $credentials,
        ]);
    }

    public function readObjectContent($bucketName, $keyName)
    {
        $s3Object = $this->s3->getObject(['Bucket' => $bucketName, 'Key' => $keyName]);
        return (string) $s3Object['Body'];
    }


    public function uploadContentToObject($content, $bucketName, $keyName)
    {
        // $setsContent = json_encode($content);
        $this->s3->putObject(['Bucket' => $bucketName, 'Body' => ($content), 'Key' => $keyName]);
    }

}


?>