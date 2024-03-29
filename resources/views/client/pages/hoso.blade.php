@extends('client.layouts.master1')
@section('content')
    <div class="container mx-auto py-8 ">
        <!-- Grid with Two Columns -->
        <div class="grid grid-cols-[300px_minmax(800px,_1fr)_100px] gap-4 mt-28">
            <!-- First Column -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md">
                <div class="p-6">
                    <div class="avatar size-24">
                        <img class="rounded-full"
                            src="/public/uploads/anh_phong/media_65f7f5b96f986.Screenshot 2023-09-24 001213.png"
                            alt="avatar" />
                    </div>

                </div>
            </div>
            <!-- Second Column -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md">
                <p class="text-2xl font-semibold mt-4 ms-3">Thông tin cá nhân</p>
                <div class="p-6 grid grid-cols-2 gap-4">
                    <label class="block">
                        <span>Họ và Tên:</span>
                        <input
                            class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" />
                    </label>

                    <label class="block">
                        <span>Ngày sinh:</span>
                        <input
                            class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" />
                    </label>

                    
                    <label class="block">
                        <span>Giới tính:</span>
                        <input
                            class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" />
                    </label>

                    <label class="block">
                        <span>Email:</span>
                        <input
                            class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" />
                    </label>

                    <label class="block">
                        <span>Số điện thoại:</span>
                        <input
                            class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" />
                    </label>

                    <label class="block">
                        <span>Địa chỉ:</span>
                        <input
                            class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" />
                    </label>
                </div>
            </div>
        </div>
    </div>
@endsection
