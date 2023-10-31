<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    {{ ucfirst(request()->segment(2)) }}
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    @if(request()->segment(2) != 'dashboard')
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ ucfirst(request()->segment(2)) }}</a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active">{{ $title ?? 'Dashboard' }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
