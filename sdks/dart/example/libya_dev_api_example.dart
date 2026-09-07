import 'package:libya_dev_api/libya_dev_api.dart';

Future<void> main() async {
  final api = LibyaDevApi(baseUrl: 'https://example.com/api/v1');

  final result = await api.nearestMunicipalities(32.8872, 13.1913, limit: 3);
  print(result['data']);

  api.close();
}
