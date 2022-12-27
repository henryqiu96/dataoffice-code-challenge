<?php
class CardController
{
    private $setsContent;
    private $s3Wrapper;
    private $sourceMetadata;
    public function __construct($s3Wrapper, $sourceMetadata)
    {
        $this->s3Wrapper = $s3Wrapper;
        $this->sourceMetadata = $sourceMetadata;
        $this->setsContent = $this->readSourceData();
    }

    public function processRequest($requestMethod, $cardId, $propertiesToUpdate)
    {
        switch ($requestMethod) {
            case 'GET':
                if ($cardId) {
                    $response = $this->getCard($cardId);
                } else {
                    $response = $this->getCardCollection();
                }
                break;
            case 'PUT':
                $response = $this->updateCard($cardId, $propertiesToUpdate);
                break;
            default:
                $response = $this->buildNotFoundResponse();
                break;
        }
        return $response;
    }

    private function getCardCollection()
    {
        return $this->buildSuccessfulResponse($this->setsContent);
    }
    private function getCard($id)
    {
        $sets = $this->parseSetsContent();
        foreach ($sets as $setContent) {
            $cards = $setContent['cards'];
            foreach ($cards as $card) {
                $cardId = $card['uuid'];
                if ($id == $cardId) {
                    return $this->buildSuccessfulResponse(json_encode($card));
                }
            }
        }
        return $this->buildNotFoundResponse();
    }

    private function updateCard($id, $propertiesToUpdate)
    {
        $sets = $this->parseSetsContent();
        if (!$this->propertiesAreValid($propertiesToUpdate, $sets)) {
            return $this->buildUnprocessableEntityResponse();
        }

        foreach ($sets as $setPosition => $setContent) {
            $cards = $setContent['cards'];
            foreach ($cards as $cardPosition => $card) {
                $cardId = $card['uuid'];
                if ($id == $cardId) {
                    echo $sets[$setPosition]['cards'][$cardPosition]['name'];
                    $cards[$cardPosition] = array_replace($card, $propertiesToUpdate);
                    $sets[$setPosition]['cards'] = $cards;
                    $this->updateSourceData($sets);
                    echo $sets[$setPosition]['cards'][$cardPosition]['name'];
                    return $this->buildSuccessfulResponse(null);

                }
            }
        }

        return $this->buildNotFoundResponse();
    }

    /* This function does a simple property type validation (the passed
    property is valid if its type is the same as the source data). Note that we
    just take the first card in the set as reference, this is definitely not
    the ideal solution. Ideally, we should have the schema of all properties as
    metadata and validate the properties against that schema. Deeper
    understanding of the dataset might be required for a more complex
    validation*/
    private function propertiesAreValid($properties, $sets)
    {
        $firstSet = reset($sets);
        $firstCard = $firstSet['cards'][0];
        foreach ($properties as $key => $value) {
            if (key_exists($key, $firstCard) && gettype($value) != gettype($firstCard[$key])) {
                return false;
            }
        }
        return true;
    }

    private function parseSetsContent()
    {
        return json_decode($this->setsContent, true);
    }

    private function readSourceData()
    {
        return $this->s3Wrapper->readObjectContent($this->sourceMetadata['bucketName'], $this->sourceMetadata['keyName']);
    }

    private function updateSourceData($sets)
    {
        $setsContent = json_encode($sets);
        $this->s3Wrapper->uploadContentToObject($setsContent, $this->sourceMetadata['bucketName'], 'NewAllPrintings.json'); //$this->sourceMetadata['keyName']
    }

    private function buildSuccessfulResponse($body)
    {
        $response['statusCodeHeader'] = 'HTTP/1.1 200 OK';
        $response['body'] = $body;
        return $response;
    }

    private function buildNotFoundResponse()
    {
        $response['statusCodeHeader'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
    private function buildUnprocessableEntityResponse()
    {
        $response['statusCodeHeader'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

}

?>