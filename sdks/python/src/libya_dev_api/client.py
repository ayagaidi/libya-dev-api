from __future__ import annotations

import json
from typing import Any, Callable
from urllib.error import HTTPError
from urllib.parse import urlencode
from urllib.request import Request, urlopen

Transport = Callable[[str, str, bytes | None], tuple[int, dict[str, Any]]]


class LibyaDevApiError(RuntimeError):
    def __init__(self, message: str, status: int, payload: dict[str, Any]):
        super().__init__(message)
        self.status = status
        self.payload = payload


class LibyaDevApi:
    def __init__(self, base_url: str, *, timeout: float = 10, transport: Transport | None = None):
        self.base_url = base_url.rstrip("/")
        self.timeout = timeout
        self.transport = transport or self._default_transport

    def _request(self, path: str, *, method: str = "GET", query: dict[str, Any] | None = None, body: dict[str, Any] | None = None) -> dict[str, Any]:
        query = {key: value for key, value in (query or {}).items() if value is not None and value != ""}
        url = f"{self.base_url}{path}"
        if query:
            url = f"{url}?{urlencode(query)}"
        encoded = json.dumps(body).encode() if body is not None else None
        status, payload = self.transport(method, url, encoded)
        if status >= 400:
            raise LibyaDevApiError(str(payload.get("message", "Libya Dev API request failed")), status, payload)
        return payload

    def _default_transport(self, method: str, url: str, body: bytes | None) -> tuple[int, dict[str, Any]]:
        headers = {"Accept": "application/json"}
        if body is not None:
            headers["Content-Type"] = "application/json"
        request = Request(url, data=body, headers=headers, method=method)
        try:
            with urlopen(request, timeout=self.timeout) as response:
                return response.status, json.loads(response.read().decode())
        except HTTPError as error:
            payload = json.loads(error.read().decode())
            return error.code, payload

    def meta(self): return self._request("/meta")
    def municipalities(self, q: str | None = None): return self._request("/locations/municipalities", query={"q": q})
    def municipality(self, slug: str): return self._request(f"/locations/municipalities/{slug}")
    def cities(self, q: str | None = None): return self._request("/locations/cities", query={"q": q})
    def municipality_points(self, mapped_only: bool = False): return self._request("/geo/municipality-points", query={"mapped_only": 1 if mapped_only else None})
    def nearest_municipalities(self, lat: float, lng: float, limit: int = 5): return self._request("/geo/nearest", query={"lat": lat, "lng": lng, "limit": limit})
    def nearby_municipalities(self, lat: float, lng: float, radius_km: float = 50, limit: int = 20): return self._request("/geo/nearby", query={"lat": lat, "lng": lng, "radius_km": radius_km, "limit": limit})
    def normalize_phone(self, phone: str): return self._request("/phone/normalize", method="POST", body={"phone": phone})
    def validate_phone(self, phone: str): return self._request("/phone/validate", method="POST", body={"phone": phone})
    def telecom_operators(self): return self._request("/telecom/operators")
    def banks(self, q: str | None = None, city: str | None = None): return self._request("/banks", query={"q": q, "city": city})
    def bank(self, slug: str): return self._request(f"/banks/{slug}")
    def holidays(self, year: int | None = None): return self._request(f"/holidays/{year}" if year else "/holidays")
    def exchange_rates(self): return self._request("/exchange-rates")
    def exchange_rate(self, currency: str): return self._request(f"/exchange-rates/{currency.upper()}")
    def convert_currency(self, amount: float, from_currency: str, to_currency: str, rate_type: str = "average"): return self._request("/currency/convert", method="POST", body={"amount": amount, "from": from_currency, "to": to_currency, "rate_type": rate_type})
    def is_business_day(self, date: str): return self._request("/calendar/is-business-day", query={"date": date})
    def next_business_day(self, date: str): return self._request("/calendar/next-business-day", query={"date": date})
    def business_days(self, start: str, end: str): return self._request("/calendar/business-days", query={"from": start, "to": end})
