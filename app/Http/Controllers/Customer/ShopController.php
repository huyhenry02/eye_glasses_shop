<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function showIndex()
    {
        $products = Product::with('category')
            ->where('is_active', 1)
            ->latest()
            ->get();

        $featuredProducts = $products->take(3)->values();
        $highlightProduct = $products->first();
        $newArrivalProducts = $products->skip(1)->take(4)->values();
        $inspiredProducts = $products->skip(5)->take(8)->values();
        $blogs = collect($this->getBlogPosts());
        $selectedBlogs = $blogs->slice(2, 3)->values();
        return view('customer.pages.index', [
            'featuredProducts' => $featuredProducts,
            'highlightProduct' => $highlightProduct,
            'newArrivalProducts' => $newArrivalProducts,
            'inspiredProducts' => $inspiredProducts,
            'selectedBlogs' => $selectedBlogs,
        ]);
    }

    public function showContact()
    {
        return view('customer.pages.contact');
    }

    public function showBlog()
    {
        $blogs = collect($this->getBlogPosts());

        return view('customer.pages.blog', [
            'blogs' => $blogs,
            'recentBlogs' => $blogs->take(4),
            'blogCategories' => $blogs->groupBy('category')->map->count(),
        ]);
    }

    public function showBlogDetail(string $slug)
    {
        $blogs = collect($this->getBlogPosts());
        $blog = $blogs->firstWhere('slug', $slug);

        abort_if(!$blog, 404);

        $recentBlogs = $blogs
            ->where('slug', '!=', $slug)
            ->take(4)
            ->values();

        $relatedBlogs = $blogs
            ->where('slug', '!=', $slug)
            ->where('category', $blog['category'])
            ->take(2)
            ->values();

        return view('customer.pages.blog-detail', [
            'blog' => $blog,
            'recentBlogs' => $recentBlogs,
            'relatedBlogs' => $relatedBlogs,
            'blogCategories' => $blogs->groupBy('category')->map->count(),
        ]);
    }

    public function showProducts(Request $request)
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();

        $query = Product::with('category')
            ->where('is_active', 1);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under_500':
                    $query->whereRaw('COALESCE(discount_price, price) < 500000');
                    break;
                case '500_1000':
                    $query->whereRaw('COALESCE(discount_price, price) >= 500000 AND COALESCE(discount_price, price) <= 1000000');
                    break;
                case '1000_2000':
                    $query->whereRaw('COALESCE(discount_price, price) > 1000000 AND COALESCE(discount_price, price) <= 2000000');
                    break;
                case 'over_2000':
                    $query->whereRaw('COALESCE(discount_price, price) > 2000000');
                    break;
            }
        }

        $sort = $request->get('sort', 'latest');

        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            default:
                $query->latest();
                break;
        }

        $perPage = (int) $request->get('per_page', 9);
        if (!in_array($perPage, [9, 12, 15, 18])) {
            $perPage = 9;
        }

        $products = $query->paginate($perPage)->withQueryString();

        return view('customer.pages.products', [
            'categories' => $categories,
            'products' => $products,
            'sort' => $sort,
            'perPage' => $perPage,
            'selectedCategory' => $request->get('category_id'),
            'selectedPriceRange' => $request->get('price_range'),
        ]);
    }

    public function showProductDetail(Product $product)
    {
        $product->load('category');

        $productsFeatured = Product::where('is_featured', 1)
            ->where('id', '!=', $product->id)
            ->take(8)
            ->get();

        return view('customer.pages.product-detail', [
            'product' => $product,
            'productsFeatured' => $productsFeatured
        ]);
    }

    private function getBlogPosts(): array
    {
        return [
            [
                'slug' => 'cach-chon-gong-kinh-phu-hop-voi-khuon-mat',
                'title' => 'Cách chọn gọng kính phù hợp với khuôn mặt phổ biến',
                'excerpt' => 'Gợi ý cách chọn gọng kính giúp khuôn mặt cân đối hơn, dễ phối đồ và phù hợp với nhu cầu sử dụng hằng ngày.',
                'content' => [
                    'Chọn gọng kính phù hợp với khuôn mặt là một trong những yếu tố quan trọng giúp tổng thể gương mặt hài hòa hơn. Với khuôn mặt tròn, bạn nên ưu tiên các mẫu kính vuông hoặc chữ nhật để tạo cảm giác cân đối.',
                    'Nếu bạn có khuôn mặt vuông, những mẫu kính bo tròn hoặc oval sẽ giúp đường nét trở nên mềm mại hơn. Với khuôn mặt trái xoan, bạn có thể linh hoạt lựa chọn nhiều kiểu gọng khác nhau.',
                    'Ngoài kiểu dáng, màu sắc gọng kính cũng ảnh hưởng nhiều đến phong cách. Tông đen, xám và nâu thường dễ phối đồ, trong khi các màu sáng hoặc trong suốt tạo cảm giác trẻ trung, hiện đại.',
                    'Khi chọn kính, bạn cũng nên quan tâm tới chất liệu gọng, trọng lượng và độ ôm khuôn mặt để đảm bảo sự thoải mái khi đeo trong thời gian dài.'
                ],
                'image' => '/customer/img/blog/main-blog/m-blog-1.png',
                'category' => 'Tư vấn chọn kính',
                'author' => 'Admin',
                'published_at' => '2026-03-12',
                'tags' => ['gọng kính', 'khuôn mặt', 'tư vấn'],
            ],
            [
                'slug' => 'meo-bao-quan-kinh-mat-ben-dep',
                'title' => 'Mẹo bảo quản kính mắt bền đẹp và hạn chế trầy xước',
                'excerpt' => 'Những thói quen đơn giản nhưng rất hiệu quả giúp kính mắt luôn sạch, bền và sử dụng được lâu hơn.',
                'content' => [
                    'Để kính mắt bền đẹp, bạn nên cất kính vào hộp khi không sử dụng. Đây là cách đơn giản nhất để hạn chế va đập và trầy xước trên bề mặt tròng kính.',
                    'Không nên úp trực tiếp mặt tròng kính xuống bàn vì điều này rất dễ làm xước lớp phủ. Khi lau kính, nên dùng khăn mềm chuyên dụng thay vì dùng áo hoặc khăn giấy khô.',
                    'Ngoài ra, bạn cũng nên vệ sinh kính định kỳ bằng dung dịch chuyên dụng để loại bỏ bụi bẩn, dầu và mồ hôi bám trên gọng và tròng kính.',
                    'Việc bảo quản đúng cách không chỉ giúp kính đẹp hơn mà còn duy trì chất lượng thị lực và cảm giác dễ chịu khi đeo.'
                ],
                'image' => '/customer/img/blog/main-blog/m-blog-2.png',
                'category' => 'Bảo quản kính',
                'author' => 'Admin',
                'published_at' => '2026-03-14',
                'tags' => ['bảo quản', 'tròng kính', 'vệ sinh kính'],
            ],
            [
                'slug' => 'kinh-chong-anh-sang-xanh-co-thuc-su-can-thiet',
                'title' => 'Kính chống ánh sáng xanh có thực sự cần thiết?',
                'excerpt' => 'Tìm hiểu khi nào nên dùng kính chống ánh sáng xanh và cách chọn sản phẩm phù hợp với nhu cầu học tập, làm việc.',
                'content' => [
                    'Kính chống ánh sáng xanh ngày càng được nhiều người quan tâm, đặc biệt là học sinh, sinh viên và nhân viên văn phòng thường xuyên sử dụng máy tính và điện thoại.',
                    'Loại kính này hỗ trợ giảm chói, tạo cảm giác dễ chịu hơn cho mắt khi làm việc với màn hình trong thời gian dài. Tuy nhiên, lựa chọn sản phẩm cần dựa trên nhu cầu thực tế thay vì chỉ chạy theo xu hướng.',
                    'Khi mua kính chống ánh sáng xanh, bạn nên chú ý đến chất lượng tròng kính, độ trong, màu sắc và cảm giác khi đeo. Một sản phẩm tốt cần mang lại sự thoải mái và không làm biến đổi màu sắc quá nhiều.',
                    'Bên cạnh đó, việc nghỉ mắt đúng cách, điều chỉnh độ sáng màn hình và duy trì khoảng cách nhìn hợp lý cũng rất quan trọng.'
                ],
                'image' => '/customer/img/blog/main-blog/m-blog-3.png',
                'category' => 'Kiến thức kính mắt',
                'author' => 'Admin',
                'published_at' => '2026-03-16',
                'tags' => ['ánh sáng xanh', 'màn hình', 'kính văn phòng'],
            ],
            [
                'slug' => 'nhung-tieu-chi-khi-chon-kinh-chong-nang',
                'title' => 'Những tiêu chí nên biết khi chọn kính chống nắng hằng ngày',
                'excerpt' => 'Kính chống nắng không chỉ cần đẹp mà còn cần phù hợp với nhu cầu bảo vệ mắt và tần suất sử dụng ngoài trời.',
                'content' => [
                    'Khi chọn kính chống nắng, điều đầu tiên bạn nên quan tâm là khả năng bảo vệ mắt khỏi tia UV. Đây là yếu tố quan trọng hơn cả yếu tố thời trang.',
                    'Tiếp theo là màu tròng, kiểu dáng và chất liệu gọng. Một chiếc kính đẹp cần hài hòa với khuôn mặt, đồng thời đủ chắc chắn để sử dụng thường xuyên.',
                    'Nếu bạn di chuyển ngoài trời nhiều, nên ưu tiên những mẫu kính nhẹ, ôm mặt và dễ phối đồ. Với người lái xe, kính chống chói hoặc tròng phân cực cũng là lựa chọn đáng cân nhắc.',
                    'Một chiếc kính chống nắng phù hợp sẽ giúp bạn vừa bảo vệ mắt vừa hoàn thiện phong cách hằng ngày.'
                ],
                'image' => '/customer/img/blog/main-blog/m-blog-4.png',
                'category' => 'Tư vấn chọn kính',
                'author' => 'Admin',
                'published_at' => '2026-03-18',
                'tags' => ['kính chống nắng', 'UV', 'tròng kính'],
            ],
            [
                'slug' => 'xu-huong-kinh-mat-thoi-trang-nam-nu',
                'title' => 'Xu hướng kính mắt thời trang nam nữ được ưa chuộng',
                'excerpt' => 'Cập nhật những kiểu gọng kính đang được yêu thích, dễ đeo và phù hợp với nhiều phong cách khác nhau.',
                'content' => [
                    'Trong thời gian gần đây, các mẫu kính gọng mảnh, kính trong suốt và kính dáng vuông đang được nhiều người yêu thích nhờ tính ứng dụng cao.',
                    'Các thiết kế tối giản tiếp tục là xu hướng nổi bật vì dễ phối với trang phục công sở, casual hoặc phong cách trẻ trung thường ngày.',
                    'Ngoài ra, những gam màu trung tính như đen, bạc, xám và nâu vẫn luôn là lựa chọn an toàn và được ưa chuộng nhất.',
                    'Việc lựa chọn một mẫu kính hợp xu hướng nhưng vẫn phù hợp với gương mặt và nhu cầu thực tế sẽ giúp bạn sử dụng lâu dài hơn.'
                ],
                'image' => '/customer/img/blog/main-blog/m-blog-5.png',
                'category' => 'Xu hướng kính mắt',
                'author' => 'Admin',
                'published_at' => '2026-03-20',
                'tags' => ['xu hướng', 'kính thời trang', 'nam nữ'],
            ],
        ];
    }
}
