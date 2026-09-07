import json
import unittest
from urllib.parse import parse_qs, urlparse

from libya_dev_api import LibyaDevApi, LibyaDevApiError


class ClientTest(unittest.TestCase):
    def test_nearest_builds_query(self):
        seen = {}

        def transport(method, url, body):
            seen["method"] = method
            seen["url"] = url
            return 200, {"data": [{"slug": "tripoli"}]}

        api = LibyaDevApi("https://api.example.ly/api/v1/", transport=transport)
        result = api.nearest_municipalities(32.8872, 13.1913, 3)
        query = parse_qs(urlparse(seen["url"]).query)
        self.assertEqual(result["data"][0]["slug"], "tripoli")
        self.assertEqual(query["limit"], ["3"])

    def test_conversion_body_and_error(self):
        def transport(method, url, body):
            payload = json.loads(body.decode())
            self.assertEqual(method, "POST")
            self.assertEqual(payload["rate_type"], "buy")
            return 422, {"message": "unsupported_currency"}

        api = LibyaDevApi("https://api.example.ly/api/v1", transport=transport)
        with self.assertRaises(LibyaDevApiError) as context:
            api.convert_currency(10, "AAA", "LYD", "buy")
        self.assertEqual(context.exception.status, 422)


if __name__ == "__main__":
    unittest.main()
