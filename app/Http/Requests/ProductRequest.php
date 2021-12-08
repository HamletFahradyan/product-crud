<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Classes\Dto\ProductDto;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
        ];
    }

    /**
     * @return ProductDto
     */
    public function getDto(): ProductDto
    {
        return new ProductDto([
            'name' => $this->name,
            'user_id'    => \Auth::id()
        ]);
    }
}
