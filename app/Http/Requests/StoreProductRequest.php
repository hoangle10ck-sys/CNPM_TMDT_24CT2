<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'description' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'sku.unique' => 'Mã sản phẩm đã tồn tại.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.numeric' => 'Giá bán phải là số.',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'thumbnail.image' => 'File được chọn phải là hình ảnh.',
            'thumbnail.mimes' => 'Ảnh phải có định dạng JPG, PNG hoặc WEBP.',
            'thumbnail.max' => 'Ảnh không được vượt quá 2 MB.',
        ];
    }
}