<div class="card mx-4 my-2">
    <div class="card-header">
        <h3 class="card-title float-left">{{ $tableTop ?? '' }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ $tableHeader ?? '' }}
                </tr>
            </thead>
            <tbody>
                {{ $tableBody ?? '' }}
            </tbody>
        </table>
        {{ $tableBottom ?? '' }}
    </div>
    <!-- /.card-body -->
</div>
