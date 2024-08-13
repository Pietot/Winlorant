"""IDK"""

from datetime import datetime
from typing import Any
import requests as req
import json

API_KEY = "HDEV-284f2d01-91dd-40a7-b52b-5ae7dbbe1309"

NAME = "Plouf VoltaniX"
TAG = "9168"


def get_request_json(url: str) -> dict[str, Any]:
    """Get the json from the request

    Args:
        url (str): The url of the request

    Returns:
        str: The json of the request
    """
    request: req.Response = req.get(url, params={"api_key": API_KEY}, timeout=30)
    request_json: dict[str, Any] = dict(request.json())
    return request_json


def get_day_by_date(date_iso: str) -> str:
    """Get the day by the date in iso format

    Args:
        date_iso (str): The date in iso format

    Returns:
        str: The day of the date
    """
    date_obj = datetime.fromisoformat(date_iso.replace("Z", "+00:00"))
    day = date_obj.strftime("%A")
    return day


gameJson = get_request_json(f"https://api.henrikdev.xyz/valorant/v1/stored-matches/eu/{NAME}/{TAG}")

for key in gameJson["data"]:
    date = key["meta"]["started_at"]
    print(get_day_by_date(date))
