# MTG API

## How to use

In order to use the API, do the following from the root of the project:

1. Run `docker compose up -d`
2. Run `composer install`
3. Run `php -S 127.0.0.1:8000 -t public`
4. In another terminal, run the following commands depending on the action you're interested in:
    - Retrieve the full collection of cards: `curl http://127.0.0.1:8000/card`
    - Retrieve a single card by ID: `curl http://127.0.0.1:8000/{ID}`. An example of ID would be: `1669af17-d287-5094-b005-4b143441442f`
    - Update a single card by ID: `curl -X PUT -d {PROPERTIES} http://127.0.0.1:8000/card/{ID}`. An example of PROPERTIES would be updating the card name: `"{\"name\": \"test\"}"`

## Testing

Do the following steps to run tests:

1. Run `docker compose up -d` (if haven't done before)
2. Run `composer install` (if haven't done before)
3. Run `php -S 127.0.0.1:8000 -t public` (if haven't done before)
4. In another terminal, run `./vendor/bin/phpunit tests`

## Implementation

The implemented solution consists of a REST API. The main code is located in the `public` directory.

### Procedure

Every time a request is received, the following steps are executed:

1. Read the content of the source data from S3
2. Depending on the action:
    - Retrieve the full collection of cards:
        1. Generate a response with the whole source content
    - Retrieve a single card by ID:
        1. Parse the read source content
        2. Iterate over the card sets (parsed source content)
        3. Iterate over the cards of each card set
        4. Search whether the requested ID is the same as the current card set
        5. If so, generate a response with the content of the card set, otherwise, generate a resnponse with a not found error.
    - Update a single card by ID:
        1. Parse the read source content
        2. Validate the properties to be updated. If the validation fails, generate a response of unprocessable entity.
        3. Iterate over the card sets (parsed source content)
        4. Iterate over the cards of each card set
        5. Update the card and the set containing the card
        6. Update the changes back to the source data in S3

### Assumptions

Several assumptions were taken when implementing the solution:

- It is not clear what does `the full collection of cards` refers to in the statement. I have assumed that it refers to the whole source data containing also the set information. If it refers to only the cards without the set information, then I would have iterated over all card arrays of all sets and adding each card to an array.
- `Retrieving a card` refers to retrieving all information related to the card.
- It is not specified how the update will be, I assumed that the user would pass only the updated properties (rather than the whole updated card).
- The `ID` of a card refers to the [uuid](https://mtgjson.com/data-models/card-set/#uuid) property.
- How should the `text search` be is not specified in the statement, it is assumed that given a text provided by the user, the cards that have an exact match with the text will be returned in the response.
- I've considered that the ground truth of the data must be that JSON file, so the update must be done on that JSON file again. I'm assuming that modeling the data in another way is out of the scope of this challenge. Otherwise, storing the data in a single JSON file would definitely not be the most efficient way, especially having updates to the content.

### Dependencies
- **aws/aws-sdk-php**: used to establish connection with S3
- **phpunit/phpunit**: used to run tests
