<x-layouts.app>

    <x-slot:title>المنتجات</x-slot:title>
    <x-slot:breadcrumb>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">المنتجات</li>
            <li class="breadcrumb-item active"><a href="{{ route('home') }}"> لوحة التحكم</a></li>
        </ol>

    </x-slot:breadcrumb>

    <x-table>
        <x-slot:tableTop>
            <a class="btn btn-primary" href="{{ route('products.create') }}">اضافة منتج</a>
        </x-slot:tableTop>
        <x-slot:tableHeader>
            <th style="width: 10px">#</th>
            <th>الاسم</th>
            <th>الوصف</th>
            <th>القسم</th>
            <th></th>
        </x-slot:tableHeader>
        <x-slot:tableBody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>
                        <div class="d-block text-truncate">{{ $product->description }}</div>
                    </td>
                    <td>{{ $product->sectionName() }}</td>
                    <td>

                        <div class="felx">
                            @can('edit product')
                                <a href="{{ route('products.edit', ['product' => $product->id]) }}"
                                    class="btn btn-secondary text-white">تعديل</a>
                            @endcan
                            @can('delete product')
                                <button class="btn btn-danger text-white" data-name="{{ $product->name }}"
                                    data-id="{{ $product->id }}">حذف</button>
                                <form class="d-none" method="POST" action="{{ route('products.destroy', $product->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button id="delete-{{ $product->id }}">delete</button>
                                </form>
                            @endcan

                        </div>
                    </td>
                </tr>
            @empty
                <p class="text-center py-2 font-weight-bold">
                    لا يوجد منتجات
                </p>
            @endforelse
        </x-slot:tableBody>
        <x-slot:tableBottom>

            <div class="p-2 mr-5">
                {{ $products->links() }}
            </div>

        </x-slot:tableBottom>
    </x-table>

    @push('bodyScripts')
        <script>
            const elements = document.querySelectorAll('.btn-danger');
            console.log("{{ route('products.index') }}");

            function deleteEvent(e) {
                const productId = e.target.getAttribute('data-id');
                const productName = e.target.getAttribute('data-name');
                Swal.fire({
                    title: `هل انت متأكد من انك تريد حذف القسم ${productName}`,
                    showCancelButton: true,
                    confirmButtonText: 'حذف',
                    cancelButtonText: 'الغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-${productId}`).click();
                    }
                });
            }


            elements.forEach((item) => {
                item.addEventListener('click', deleteEvent);
            });
        </script>
    @endpush
</x-layouts.app>
