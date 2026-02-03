<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignRolePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // SuperAdmin - sudah punya semua, skip
        
        // KEPALA DESA - Full access kecuali super admin features
        $kades = Role::findByName('kepala_desa');
        $kadesPermissions = Permission::where('name', 'not like', '%shield::role%')
            ->pluck('name')
            ->toArray();
        $kades->givePermissionTo($kadesPermissions);
        $this->command->info('✓ Kepala Desa: ' . count($kadesPermissions) . ' permissions');

        // SEKRETARIS - View all, manage articles, view users
        $sekretaris = Role::findByName('sekretaris');
        $sekretarisPermissions = Permission::whereIn('name', [
            // View semua
            'view_any_keluarga', 'view_keluarga',
            'view_any_penduduk', 'view_penduduk',
            'view_any_dusun', 'view_dusun',
            'view_any_activity', 'view_activity',
            'view_any_k::k::extraction::history', 'view_k::k::extraction::history',
            'view_any_user', 'view_user',
            // Manage artikel
            'view_any_article', 'view_article', 'create_article', 'update_article', 'delete_article',
            // Pages
            'page_MyProfilePage', 'widget_Stats', 'widget_ApplicationInfo',
        ])->pluck('name')->toArray();
        $sekretaris->givePermissionTo($sekretarisPermissions);
        $this->command->info('✓ Sekretaris: ' . count($sekretarisPermissions) . ' permissions');

        // OPERATOR - Full CRUD kecuali user management
        $operator = Role::findByName('operator');
        $operatorPermissions = Permission::where(function($q) {
            $q->where('name', 'like', '%keluarga%')
              ->orWhere('name', 'like', '%penduduk%')
              ->orWhere('name', 'like', '%k::k::extraction::history%')
              ->orWhere('name', 'like', '%dusun%')
              ->orWhere('name', 'like', '%map::point%')
              ->orWhere('name', 'like', 'page_%')
              ->orWhere('name', 'like', 'widget_%');
        })
        ->where('name', 'not like', '%force_delete%')
        ->where('name', 'not like', '%shield::role%')
        ->where('name', 'not like', '%user%')
        ->pluck('name')->toArray();
        $operator->givePermissionTo($operatorPermissions);
        $this->command->info('✓ Operator: ' . count($operatorPermissions) . ' permissions');

        // PENGELOLA DATA - Full data management
        $pengelolaData = Role::findByName('pengelola_data');
        $pengelolaDataPermissions = Permission::where(function($q) {
            $q->where('name', 'like', '%keluarga%')
              ->orWhere('name', 'like', '%penduduk%')
              ->orWhere('name', 'like', '%k::k::extraction::history%')
              ->orWhere('name', 'like', '%dusun%')
              ->orWhere('name', 'like', '%activity%')
              ->orWhere('name', 'like', 'page_Ekstraksi%')
              ->orWhere('name', 'like', 'widget_%');
        })
        ->where('name', 'not like', '%shield::role%')
        ->where('name', 'not like', '%user%')
        ->pluck('name')->toArray();
        $pengelolaData->givePermissionTo($pengelolaDataPermissions);
        $this->command->info('✓ Pengelola Data: ' . count($pengelolaDataPermissions) . ' permissions');

        // KADUS - View own dusun + import KK
        $kadus = Role::findByName('kadus');
        $kadusPermissions = Permission::whereIn('name', [
            // View/manage data dusun sendiri (akan di-filter di policy)
            'view_any_keluarga', 'view_keluarga', 'create_keluarga', 'update_keluarga',
            'view_any_penduduk', 'view_penduduk', 'create_penduduk', 'update_penduduk',
            'view_any_k::k::extraction::history', 'view_k::k::extraction::history',
            'view_any_dusun', 'view_dusun',
            // Ekstraksi KK page
            'page_EkstraksiKartuKeluarga',
            // Profile & widgets
            'page_MyProfilePage',
            'widget_Stats',
            'widget_ApplicationInfo',
        ])->pluck('name')->toArray();
        $kadus->givePermissionTo($kadusPermissions);
        $this->command->info('✓ Kadus: ' . count($kadusPermissions) . ' permissions');

        // FARMER - Limited access (legacy role, mostly view only)
        $farmer = Role::findByName('farmer');
        $farmerPermissions = Permission::whereIn('name', [
            'view_any_map::point', 'view_map::point',
            'view_any_article', 'view_article',
            'page_MyProfilePage',
            'widget_Stats',
        ])->pluck('name')->toArray();
        $farmer->givePermissionTo($farmerPermissions);
        $this->command->info('✓ Farmer: ' . count($farmerPermissions) . ' permissions');

        $this->command->info('');
        $this->command->info('✅ All role permissions assigned successfully!');
    }
}
