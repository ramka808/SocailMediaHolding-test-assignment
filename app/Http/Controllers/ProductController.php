<?php
namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $response = Http::get('https://dummyjson.com/products/search?q=iphone');
       
        $products = $response->json()['products'];

        $rules = ['id'            => 'required|unique:products',
            'title'                   => 'required|string',
            'description'             => 'required|string',
            'category'                => 'required|string',
            'price'                   => 'required|numeric',
            'discountPercentage'      => 'required|numeric',
            'rating'                  => 'required|numeric',
            'stock'                   => 'required|numeric',
            'tags'                    => 'required|array',
            'tags.*'                  => 'string',
            'brand'                   => 'required|string',
            'sku'                     => 'required|string',
            'weight'                  => 'required|numeric',
            'dimensions'              => 'required|array',
            'dimensions.width'        => 'required|numeric',
            'dimensions.height'       => 'required|numeric',
            'dimensions.depth'        => 'required|numeric',
            'warrantyInformation'     => 'required|string',
            'shippingInformation'     => 'required|string',
            'availabilityStatus'      => 'required|string',
            'reviews'                 => 'required|array',
            'reviews.*'               => 'array',
            'reviews.*.rating'        => 'required|numeric',
            'reviews.*.comment'       => 'required|string',
            'reviews.*.date'          => 'required|string',
            'reviews.*.reviewerName'  => 'required|string',
            'reviews.*.reviewerEmail' => 'required|email',
            'returnPolicy'            => 'required|string',
            'minimumOrderQuantity'    => 'required|integer',
            'meta'                    => 'required|array',
            'meta.barcode'            => 'required|string',
            'meta.createdAt'          => 'required|string',
            'meta.qrCode'             => 'required|string',
            'meta.updatedAt'          => 'required|string',
            'images'                  => 'required|array',
            'images.*'                => 'string',
            'thumbnail'               => 'required|string'];

        $importedCount = 0;
        $skippedCount  = 0;
        $errors        = [];

        foreach ($products as $product) {
            try {
                // Пропускаем категорию, отличную от smartphones
                if ($product['category'] != 'smartphones') {
                    continue;
                }

                // Проверяем существование продукта
                if (Product::where('id', $product['id'])->exists()) {
                    $skippedCount++;
                    continue;
                }

                DB::beginTransaction();

                // Валидация
                $validator = Validator::make($product, $rules);
                if ($validator->fails()) {
                    $errors[] = [
                        'product_id' => $product['id'],
                        'errors'     => $validator->errors()->toArray(),
                    ];
                    continue;
                }

                Product::create([
                    'id'                   => $product['id'],
                    'title'                => $product['title'],
                    'description'          => $product['description'],
                    'category'             => $product['category'],
                    'price'                => $product['price'],
                    'discountPercentage'   => $product['discountPercentage'],
                    'rating'               => $product['rating'],
                    'stock'                => $product['stock'],
                    'tags'                 => json_encode($product['tags']),
                    'brand'                => $product['brand'],
                    'sku'                  => $product['sku'],
                    'weight'               => $product['weight'],
                    'width'                => $product['dimensions']['width'],
                    'height'               => $product['dimensions']['height'],
                    'depth'                => $product['dimensions']['depth'],
                    'warrantyInformation'  => $product['warrantyInformation'],
                    'shippingInformation'  => $product['shippingInformation'],
                    'availabilityStatus'   => $product['availabilityStatus'],
                    'returnPolicy'         => $product['returnPolicy'],
                    'minimumOrderQuantity' => $product['minimumOrderQuantity'],
                    'created_at'           => $product['meta']['createdAt'],
                    'updated_at'           => $product['meta']['updatedAt'],
                    'barcode'              => $product['meta']['barcode'],
                    'qrCode'               => $product['meta']['qrCode'],
                    'thumbnail'            => $product['thumbnail'],
                ]);
                foreach ($product['images'] as $imageUrl) {
                    Image::create([
                        'product_id' => $product['id'],
                        'image'      => $imageUrl,
                    ]);
                }
                foreach ($product['reviews'] as $review) {
                    Review::create([
                        'product_id'     => $product['id'],
                        'rating'         => $review['rating'],
                        'comment'        => $review['comment'],
                        'date'           => $review['date'],
                        'reviewer_name'  => $review['reviewerName'],
                        'reviewer_email' => $review['reviewerEmail'],
                    ]);
                }

                DB::commit();
                $importedCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = [
                    'product_id' => $product['id'] ?? 'unknown',
                    'error'      => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'message'        => 'Import process completed',
            'imported_count' => $importedCount,
            'skipped_count'  => $skippedCount,
            'errors'         => $errors,
        ]);

    }
    public function show()
    {
        $products = Product::with('reviews', 'images')->get();
        return response()->json($products);
    }
}
