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
    
        $adminRole = Role::create(['name' => 'admin']);
     
        $adminPermissions = Permission::pluck('id','id')->all();
   
        $adminRole->syncPermissions($adminPermissions);
        
        $user->assignRole([$adminRole->id]); 

     
        

        $outletUserPermissions = [
            'login', 
            'logout', 
            'password.request', 
            'password.email', 
            'password.reset', 
            'password.update', 
            'password.confirm', 
            'home', 
            'machine.index', 
            'machine.create', 
            'machine.store', 
            'machine.show', 
            'machine.edit', 
            'machine.update', 
            'machine.destroy', 
            'distribute.index', 
            'distribute.create', 
            'distribute.store', 
            'distribute.show', 
            'distribute.edit', 
            'distribute.update', 
            'distribute.destroy',
            'distribute.preview',
            'listdistributedetail', 
            'outletdistribute.index', 
            'outletdistribute.create', 
            'outletdistribute.store', 
            'outletdistribute.edit', 
            'outletdistribute.update', 
            'outletdistribute.show', 
            'sellingprice.index', 
            'sellingprice.create', 
            'sellingprice.store', 
            'sellingprice.show', 
            'sellingprice.edit', 
            'sellingprice.update', 
            'sellingprice.destroy',
            'sellingprice.deactivate', 
            'sellingprice.activate', 
            'sellingprice.toggle', 
            'sellingprice.updateStatus', 
            'sellingprice.sell',  
            'issue.create', 
            'issue.store', 
            'issue.edit', 
            'issue.update', 
            'issue.index', 
            'issue.show', 
            'search-list-distribute-detail', 
            'search-reset', 
            'search', 
            'search-outlet-distributes', 
            'search-outlet-issue', 
            'outletmachineitem', 
            'distribute-products.index', 
            'distribute-products.create', 
            'distribute-products.store', 
            'distribute-products.show', 
            'distribute-products.edit', 
            'distribute-products.update', 
            'distribute-products.destroy', 
            'courses.deactivate', 
            'courses.activate',

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

        $outletUserRole = Role::create(['name' => 'outlet_user']);
        $posUserRole = Role::create(['name' => 'pos_user']);

        $outletUserPermissions = Permission::whereIn('name', $outletUserPermissions)->pluck('id')->all();
        $posUserPermissions = Permission::whereIn('name', $posUserPermissions)->pluck('id')->all();

        $outletUserRole->syncPermissions($outletUserPermissions);
        $posUserRole->syncPermissions($posUserPermissions);

        
        
    }
}