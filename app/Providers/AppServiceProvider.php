<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Builder::macro('whereLike', function ($columns, $search) {
            $this->where(function ($query) use ($columns, $search) {
                foreach (array_wrap($columns) as $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
            return $this;
        });

        Builder::macro('whereCompound', function ($columns, $search) {
            $this->where(function ($query) use ($columns, $search) {
                $query->where(DB::raw('CONCAT_WS(" ", ' . $columns . ')'), 'like', "%{$search}%");
            });
            return $this;
        });

        Builder::macro('searchable', function ($columns = [], $perPage = 10, $returnPaginated = true) {

            if (empty($columns)) $columns = $this->model->searchable ?: [];
            $request = request();

            if ($request->has('search')) $this->whereLike($columns, $request->search);

            if ($request->sort ||$request->order) {
                $sort = $request->get('sort', 'created_at');
                $order = $request->get('order', 'DESC');
                $this->orderBy($sort, $order);
            }
            if ($returnPaginated) return $this->paginate($request->get('perPage', $perPage))->appends($request->all());
            return $this;
        });


        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('d M Y, H:i A'); ?>";
        });

        Blade::directive('time', function ($expression) {
            return "<?php echo ($expression)->format('H:i a'); ?>";
        });
    }
}
