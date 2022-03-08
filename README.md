<br />
<br />

<p align="center">
  <img src=".doc/challenges.png" alt="dataoffice code challenge" width="80" height="80">
</p>

<br />


<h3 align="center">
  <b>
    SEAT:CODE Dataoffice Challange
  </b>
</h3>

<br />

## Introduction

Magic The Gathering (MTG, or just Magic) is a trading card game first published in 1993 by Wizards of the Coast. This game has seen immense popularity and new cards are still released every few months. The strength of different cards in the game can vary wildly and as a result some cards now sell on secondary markets for as high as thousands of dollars.

MTG JSON has an excellent collection of every single Magic Card - stored in JSON data. Version 3.6 (collected September 21, 2016) of their database is provided here.

Full documentation for the data is provided here:
http://mtgjson.com/documentation.html

Also, if you want to include images of the cards in your writeups, you can grab them from the official Wizards of the Coast website using the following URL:

http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=180607&type=card

Just replace the multiverse ID with the one provided in the mtgjson file.

## Requirements

The purpose of this challenge is to create an API able to provide information about Magic The Gathering cards. For this a bucket is provided using minio along with a big json file, `AllPrintings.json`, with all card information.

Minio is a drop-in replacement of S3 that is used in this challenge like if you were interacting with a real S3 bucket.

The main points you will have to do are the following

1. Import all the data in the bucket provided in the most efficient and performant way possible, ready to be served by an API.
2. Create an endpoint to be able to retrieve the full collection of cards.
3. Create an endpoint to be able to retrieve a single card by ID.
4. Create an endpoint to be able to update a single card by ID.
5. Update the endpoint of the cards collection to be able to perform a text search. (Optional)

The technology used preferably should be PHP, and you can use the libraries or frameworks that you want. 

### What will be assessed?

1. Clean, simple and easy-to-understand code.
2. Time-to-market is a top priority. This means the we value more than everything a simple solution and especially **easy to maintain**
3. Performance & efficiency. Note: It does not work a solution that has a really good performance but it's hard to maintain and no other in the team can touch it. 