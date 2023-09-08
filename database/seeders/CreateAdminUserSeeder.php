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
            'outletstockoverview.create',
            'outletstockoverview.store',
            'outletstockoverview.sample-export', 
           'outletstockoverview.import', 
           'report.outletstockoverview', 
            'outletstockoverview.search', 
            'outletstockoverview.reset', 
            'outletstockoverview.export', 
            'checkoutletstockoverview',
            'outletdistribute.create', 
           'outletdistribute.store', 
           'issue.create', 
           'issue.store',
           'outletleveloverview.create', 
           'outletleveloverview.store',
            'outletleveloverview.index',
            'checkoutletleveloverview',
            'outletleveloverview.export', 
           'outletleveloverview.search', 
           'outletleveloverview.reset',
           'outletlevelopeningqty.sample-export', 
           'outletlevelopeningqty.import', 
           'adjustment.index', 
            'adjustment.create', 
            'adjustment.store', 
            'adjustment.show', 
           'adjustment.edit', 
           'adjustment.update', 
            'adjustment.destroy', 
             'adjustment.export', 
             'search-list-adjustment',
             'adjustment-search-reset',
            'damage.index', 
           'damage.create', 
           'damage.store', 
           'damage.show', 
           'damage.edit', 
           'damage.update', 
           'damage.destroy',
           'search-list-damage',
           'damage-search-reset',
           'damage.export',
            'pos.index', 
            'pos.add', 
            'pos.product.search', 
            'positem.add', 
            'positem.update', 
            'positem.remove', 
            'positem.alert',
            'outletstockhistory.index',
            'outletstockhistory.export', 
           'outletstockhistory.search', 
           'outletstockhistory.reset', 
           'checkoutletstockhistory', 
            'outletlevelhistory.index', 
            'outletlevelhistory.export', 
            'outletlevelhistory.search', 
           'outletlevelhistory.reset',
           'checkoutletlevelhistory',
           'report.products',
           'product.reset',
           'product.export',
           'product.search',
           'distribute.index', 
            'distribute.create', 
            'distribute.store', 
            'distribute.show',
            'distribute.update',
            'search-list-distribute', 


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