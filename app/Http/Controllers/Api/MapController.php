<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
    public function geocode(Request $request)
    {
        $request->validate(['address' => 'required|string']);

        $rawAddress = $request->address;
        $address = $rawAddress . ', Việt Nam'; // BẮT BUỘC thêm

        $apiKey = config('services.google.maps_key');

        try {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $apiKey,
                'language' => 'vi',
                'region' => 'vn', // ƯU TIÊN VIỆT NAM
                'components' => 'country:VN' // BẮT BUỘC Việt Nam
            ]);

            $data = $response->json();

            if ($data['status'] === 'OK' && !empty($data['results'])) {
                $location = $data['results'][0]['geometry']['location'];
                return response()->json([
                    'success' => true,
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng'],
                    'formatted_address' => $data['results'][0]['formatted_address']
                ]);
            }

            // GHI LOG ĐỂ DEBUG
            Log::warning('Geocode failed', [
                'input' => $rawAddress,
                'full_query' => $address,
                'google_status' => $data['status'] ?? 'unknown',
                'results_count' => count($data['results'] ?? [])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ. Vui lòng nhập đầy đủ: số nhà, đường, phường/xã, quận/huyện, thành phố.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Geocode API error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối Google Maps. Vui lòng thử lại.'
            ], 500);
        }
    }
    public function placesAutocomplete(Request $request)
    {
        $request->validate(['input' => 'required|string']);

        $input = $request->input;
        $apiKey = config('services.google.maps_key');

        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', [
            'input' => $input,
            'key' => $apiKey,
            'language' => 'vi',
            'components' => 'country:vn',
            'types' => 'address'
        ])->json();

        return response()->json([
            'predictions' => $response['predictions'] ?? []
        ]);
    }

    public function placeDetails(Request $request)
    {
        $request->validate(['place_id' => 'required|string']);

        $placeId = $request->place_id;
        $apiKey = config('services.google.maps_key');

        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'key' => $apiKey,
            'language' => 'vi',
            'fields' => 'geometry,formatted_address'
        ])->json();

        if ($response['status'] === 'OK') {
            $result = $response['result'];
            return response()->json([
                'success' => true,
                'latitude' => $result['geometry']['location']['lat'],
                'longitude' => $result['geometry']['location']['lng'],
                'formatted_address' => $result['formatted_address']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không lấy được thông tin địa chỉ'
        ]);
    }
}
