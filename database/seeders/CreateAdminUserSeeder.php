<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'username' => 'admin',
            'name' => 'Admin', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'outlet_id' => 1,
        ]);
    
        $adminRole = Role::create(['name' => 'Admin']);
     
        $adminPermissions = Permission::pluck('id','id')->all();
   
        $adminRole->syncPermissions($adminPermissions);
        
        $user->assignRole([$adminRole->id]); 

        $outletUserPermissions = [
            'login',
            'logout',
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'password.confirm',
            'home',
            'outlets.index',
            'outlets.edit',
            'outlets.update',
            'distribute.index',
            'distribute.create',
            'distribute.store',
            'distribute.show',
            'distribute.update',
            'listdistributedetail',
            'distribute-detail-export',
            'outletstockhistory.index',
            'outletleveloverview.index',
            'outletleveloverview.create',
            'outletleveloverview.store',
            'adjustment.index',
            'adjustment.create',
            'adjustment.store',
            'adjustment.show',
            'adjustment.edit',
            'adjustment.update',
            'adjustment.destroy',
            'damage.index',
            'damage.create',
            'damage.store',
            'damage.show',
            'damage.edit',
            'damage.update',
            'damage.destroy',
            'sell.index',
            'sell.create',
            'sell.store',
            'sell.show',
            'sell.edit',
            'sell.update',
            'sell.destroy',
            'issue.index',
            'issue.create',
            'issue.store',
            'issue.edit',
            'issue.update',
            'issue.show',
            'outletdistribute.index',
            'outletdistribute.create',
            'outletdistribute.store',
            'outletstockoverview.create',
            'outletstockoverview.store',
            'search',
            'search-damage',
            'search-outlet-distributes',
            'search-outlet-issue',
            'search-list-distribute-detail',
            'search-bodanddepartment',
            'search-list-damage',
            'search-list-adjustment',
            'search-list-purchasedpricehistory',
            'search-list-distribute',
            'search-reset',
            'damage-search-reset',
            'adjustment-search-reset',
            'distribute-search-reset',
            'product.search',
            'product.reset',
            'pos.index',
            'pos.add',
            'pos.product.search',
            'positem.add',
            'positem.update',
            'positem.remove',
            'positem.alert',
            'report.products',
            'report.machine',
            'report.outletstockoverview',
            'report.bodanddepartment',
            'outletstockoverview.search',
            'outletstockoverview.reset',
            'outletdistribute.show',
            'product.export',
            'damage.export',
            'adjustment.export',
            'outletstockoverview.export',
            'outletstockoverview.sample-export',
            'outletstockoverview.import',
            'outletstockhistory.export',
            'outletstockhistory.search',
            'outletstockhistory.reset',
            'checkoutletstockhistory',
            'checkoutletstockoverview',
            'checkoutletlevelhistory',
            'checkoutletleveloverview',
            'outletlevelhistory.index',
            'outletlevelhistory.export',
            'outletlevelhistory.search',
            'outletlevelhistory.reset',
            'outletlevelopeningqty.sample-export',
            'outletlevelopeningqty.import',
            'getoutletItem',
            'outletleveloverview.export',
            'outletleveloverview.search',
            'outletleveloverview.reset',
            'get-machine',
            'generatedamagecode',
            'sell-search',
            'sell-search-reset',
            'sell-export',
            'generateadjcode'
        ];

        $posUserPermissions = [
            'login',
            'logout',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'password.confirm',
            'home',
            'pos.index', 
            'pos.add', 
            'pos.product.search', 
            'positem.add', 
            'positem.update', 
            'positem.remove', 
           
        ];

        $outletUserRole = Role::create(['name' => 'Outlet']);
        $posUserRole = Role::create(['name' => 'Pos']);

        $outletUserPermissions = Permission::whereIn('name', $outletUserPermissions)->pluck('id')->all();
        $posUserPermissions = Permission::whereIn('name', $posUserPermissions)->pluck('id')->all();

        $outletUserRole->syncPermissions($outletUserPermissions);
        $posUserRole->syncPermissions($posUserPermissions);

        
        
    }
}