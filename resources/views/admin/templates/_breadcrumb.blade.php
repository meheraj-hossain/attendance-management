<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    @if(request()->segment(1) == 'unauthorized')
                        Unauthorized
                    @else
                        {{ ucfirst(request()->segment(2)) }}
                    @endif

                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    @if(request()->segment(1) != 'unauthorized')
                        @if(request()->segment(2) != 'dashboard')
                            <li class="breadcrumb-item"><a href="javascript:void(0)">
                                    {{ ucfirst(request()->segment(2)) }}
                                </a>
                            </li>
                        @endif
                    @endif
                    <li class="breadcrumb-item active">{{ $title ?? 'Dashboard' }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
