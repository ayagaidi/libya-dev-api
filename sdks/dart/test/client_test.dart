import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:http/testing.dart';
import 'package:libya_dev_api/libya_dev_api.dart';
import 'package:test/test.dart';

void main() {
  test('nearestMunicipalities builds the expected query', () async {
    final client = MockClient((request) async {
      expect(request.url.queryParameters['limit'], '3');
      expect(request.url.queryParameters['lat'], '32.8872');
      return http.Response(
        jsonEncode({
          'data': [
            {'slug': 'tripoli'},
          ],
        }),
        200,
      );
    });
    final api = LibyaDevApi(
      baseUrl: 'https://api.example.ly/api/v1/',
      client: client,
    );

    final result = await api.nearestMunicipalities(
      32.8872,
      13.1913,
      limit: 3,
    );
    expect((result['data'] as List).first['slug'], 'tripoli');
  });

  test('convertCurrency sends JSON and raises API exceptions', () async {
    final client = MockClient((request) async {
      final body = jsonDecode(request.body) as Map<String, dynamic>;
      expect(body['rate_type'], 'sell');
      return http.Response(
        jsonEncode({'message': 'unsupported_currency'}),
        422,
      );
    });
    final api = LibyaDevApi(
      baseUrl: 'https://api.example.ly/api/v1',
      client: client,
    );

    expect(
      () => api.convertCurrency(
        amount: 10,
        from: 'AAA',
        to: 'LYD',
        rateType: 'sell',
      ),
      throwsA(isA<LibyaDevApiException>()),
    );
  });
}
