import 'dart:convert';

import 'package:http/http.dart' as http;

class LibyaDevApiException implements Exception {
  LibyaDevApiException(this.message, this.status, this.payload);

  final String message;
  final int status;
  final Map<String, dynamic> payload;

  @override
  String toString() => 'LibyaDevApiException($status): $message';
}

class LibyaDevApi {
  LibyaDevApi({required String baseUrl, http.Client? client})
      : baseUrl = baseUrl.replaceFirst(RegExp(r'/$'), ''),
        _client = client ?? http.Client();

  final String baseUrl;
  final http.Client _client;

  Future<Map<String, dynamic>> _request(
    String path, {
    String method = 'GET',
    Map<String, Object?> query = const {},
    Map<String, dynamic>? body,
  }) async {
    final queryParameters = <String, String>{};
    for (final entry in query.entries) {
      if (entry.value != null && entry.value.toString().isNotEmpty) {
        queryParameters[entry.key] = entry.value.toString();
      }
    }

    final uri = Uri.parse('$baseUrl$path').replace(
      queryParameters: queryParameters.isEmpty ? null : queryParameters,
    );
    final headers = body == null ? <String, String>{} : {'Content-Type': 'application/json'};
    final response = method == 'POST'
        ? await _client.post(uri, headers: headers, body: jsonEncode(body))
        : await _client.get(uri, headers: headers);
    final decoded = jsonDecode(response.body);
    final payload = Map<String, dynamic>.from(decoded as Map);

    if (response.statusCode >= 400) {
      throw LibyaDevApiException(
        payload['message']?.toString() ?? 'Libya Dev API request failed',
        response.statusCode,
        payload,
      );
    }

    return payload;
  }

  Future<Map<String, dynamic>> meta() => _request('/meta');
  Future<Map<String, dynamic>> municipalities([String? q]) => _request('/locations/municipalities', query: {'q': q});
  Future<Map<String, dynamic>> municipality(String slug) => _request('/locations/municipalities/${Uri.encodeComponent(slug)}');
  Future<Map<String, dynamic>> cities([String? q]) => _request('/locations/cities', query: {'q': q});
  Future<Map<String, dynamic>> municipalityPoints({bool mappedOnly = false}) => _request('/geo/municipality-points', query: {'mapped_only': mappedOnly ? 1 : null});
  Future<Map<String, dynamic>> nearestMunicipalities(double lat, double lng, {int limit = 5}) => _request('/geo/nearest', query: {'lat': lat, 'lng': lng, 'limit': limit});
  Future<Map<String, dynamic>> nearbyMunicipalities(double lat, double lng, {double radiusKm = 50, int limit = 20}) => _request('/geo/nearby', query: {'lat': lat, 'lng': lng, 'radius_km': radiusKm, 'limit': limit});
  Future<Map<String, dynamic>> normalizePhone(String phone) => _request('/phone/normalize', method: 'POST', body: {'phone': phone});
  Future<Map<String, dynamic>> validatePhone(String phone) => _request('/phone/validate', method: 'POST', body: {'phone': phone});
  Future<Map<String, dynamic>> telecomOperators() => _request('/telecom/operators');
  Future<Map<String, dynamic>> banks({String? q, String? city}) => _request('/banks', query: {'q': q, 'city': city});
  Future<Map<String, dynamic>> bank(String slug) => _request('/banks/${Uri.encodeComponent(slug)}');
  Future<Map<String, dynamic>> holidays([int? year]) => _request(year == null ? '/holidays' : '/holidays/$year');
  Future<Map<String, dynamic>> exchangeRates() => _request('/exchange-rates');
  Future<Map<String, dynamic>> exchangeRate(String currency) => _request('/exchange-rates/${Uri.encodeComponent(currency.toUpperCase())}');
  Future<Map<String, dynamic>> convertCurrency({required double amount, required String from, required String to, String rateType = 'average'}) => _request('/currency/convert', method: 'POST', body: {'amount': amount, 'from': from, 'to': to, 'rate_type': rateType});
  Future<Map<String, dynamic>> isBusinessDay(String date) => _request('/calendar/is-business-day', query: {'date': date});
  Future<Map<String, dynamic>> nextBusinessDay(String date) => _request('/calendar/next-business-day', query: {'date': date});
  Future<Map<String, dynamic>> businessDays(String from, String to) => _request('/calendar/business-days', query: {'from': from, 'to': to});

  void close() => _client.close();
}
