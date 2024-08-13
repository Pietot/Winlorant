import requests as req
import json

API_KEY = "HDEV-284f2d01-91dd-40a7-b52b-5ae7dbbe1309"

NAME = "Plouf VoltaniX"
TAG = "9168"
def createReq(url):
    request = req.get(url, params={"api_key" : API_KEY})
    gameJson = request.json()
    

    return gameJson

gameJson = createReq(f"https://api.henrikdev.xyz/valorant/v1/stored-matches/eu/{NAME}/{TAG}")

for i in gameJson["data"]:
    print(i["meta"]["map"]["name"])
