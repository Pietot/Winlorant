# Winlorant

![Static Badge](https://img.shields.io/badge/made_in-France-red?labelColor=blue)
![Static Badge](https://img.shields.io/badge/language-PHP-777BB3?labelColor=484C89)
![Static Badge](https://img.shields.io/badge/language-JavaScript-f0db4f?labelColor=323330)

**Winlorant** is a free and open-source website that allows you to track your winrate in Valorant. It uses the <a href="https://github.com/Henrik-3/unofficial-valorant-api" target="_blank">unofficial Henrik-3 API</a> to get your match history to calculate your winrate and others.

## Summary

### 1. [Features](#1---features)

### 2. [Usage](#2---usage)

### 3. [Contributing](#3---contributing)

### 4. [Installation](#4---installation)

### 5. [Privacy Policy](#5---privacy-policy)

## 1 - Features

- Track your winrate and percentage of headshots in Valorant by the day of the week

- Track your winrate in Valorant by the map.

- Filter the data by the episode and the act.

- Visualize the data on a beautifull, responsive and interactive chart

## 2 - Usage

To use the website, simply go to the <a href="https://winlorant.com/" target="_blank">Winlorant.com</a>.

Then go to the register page and just enter your Valorant game name and tag.

That's it! You can now see your winrate and other stats in beautiful charts.

## 3 - Contributing

This project is not fully done and can be improved. So if you want to contribute to the project or just want to see the code, go to step 4:

[Installation](#4---installation)

## 4 - Installation

To install and use the website locally, you need to follow these steps:

- ### 1 - Get an API Key

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To begin, go get an API key on the Henrik's <a href="https://discord.com/invite/X3GaVkX2YN">discord server</a>.

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;More infos [here](https://docs.henrikdev.xyz/valorant/changes/v4.0.0)</a>.

- ### 2 - Download the source code

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;After this, clone the project with the folowing command (recommended):

```bash
git clone git@github.com:Pietot/Winrate-tracker.git
```

or

```bash
git clone https://github.com/Pietot/Winrate-tracker.git
```

---

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Or download the source code directly as a zip file <a href="https://github.com/Pietot/Winrate-tracker/archive/refs/heads/main.zip">here</a>.

- ### 3 - Import the database

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Then, startup your local webserver (WAMP | XAMPP | LAMP | MAMP)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Go to `localhost/phpmyadmin` on any web browser and connect

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Create a new database and name it `winlorant`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Then select the database, go to import at the top and select the file at `src/config/winlorant.sql`

- ### 4 - Setup the .env file

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Next, go to `src/config` and create a file named `.env`

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inside this file, copy this:

```env
DB_HOST=localhost
DB_NAME=winlorant
DB_USER={the user used to connect in phpmyadmin}
DB_PASSWORD={the password used to connect in phpmyadmin}
API_KEY={the api you got at step 1}
```

- ### 5 - Create the json folder

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Finally, just create a folder name `json` in `src/`

That's all! Now you can use the website and modify it as you want!

## 5 - Privacy Policy

The website only stores your Valorant game name, tag, region in database. The other usefull data that is stored on the server is your match history to calculate your winrate and others.

Here's an example of the data stored:

```json
{
  "data": [
    {
      "meta": {
        "map": "Abyss",
        "start": "2024-08-30T13:27:09.993Z",
        "season": "e5a2",
      },
      "stats": {
        "team": "Red",
        "kill": 6,
        "assists": 4,
        "shots": {
          "head": 3,
          "body": 20,
          "leags": 1
        },
        "damage": {
          "made": 1058,
          "receive": 3033
        }
      },
      "teams": {
        "red": 10,
        "blue": 13
      }
    }
  ]
}
```
