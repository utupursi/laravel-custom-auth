<?php
/**
 *  app/Repositories/Eloquent/Base/EloquentRepositoryInterface.php
 *
 * Date-Time: 19.05.21
 * Time: 09:48
 * @author Vito Makhatadze <vitomaxatadze@gmail.com>
 */

namespace App\Repositories\Eloquent\Base;

use Illuminate\Pagination\Paginator;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories\Eloquent\Base
 */
interface EloquentRepositoryInterface
{
    /**
     * @param int $perPage
     * @param array $columns
     *
     * @return Paginator
     */
    public function paginate(int $perPage, array $columns): ?Paginator;

    /**
     * @param integer $id
     * @param array $columns
     */
    public function find(int $id, $columns = ['*']);
}