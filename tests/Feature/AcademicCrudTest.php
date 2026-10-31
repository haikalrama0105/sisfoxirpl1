<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard_and_crud(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('siswa.index'))->assertRedirect(route('login'));
        $this->get(route('nilai.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_log_in_and_log_out_and_extra_auth_routes_are_disabled(): void
    {
        $user = User::factory()->create();

        $this->get('/register')->assertNotFound();
        $this->get('/password/reset')->assertNotFound();
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('dashboard'))->assertOk();
        $this->post(route('logout'))->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_manage_master_data(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get(route('dashboard'))->assertOk();

        $kelas = Kelas::create([
            'nama_kelas' => 'XI RPL 1',
            'tingkat' => 'XI',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2026/2027',
        ]);
        $this->get('/kelas')->assertNotFound();

        $this->post(route('siswa.store'), [
            'nis' => '1001',
            'nama' => 'Nama Siswa',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
        ])->assertRedirect(route('siswa.index'));
        $siswa = Siswa::firstOrFail();
        $this->get(route('siswa.index'))->assertOk();
        $this->get(route('siswa.show', $siswa))->assertOk();
        $this->get(route('siswa.edit', $siswa))->assertOk();

        $this->put(route('siswa.update', $siswa), [
            'nis' => '1001',
            'nama' => 'Nama Siswa Diperbarui',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ])->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswas', ['id' => $siswa->id, 'nama' => 'Nama Siswa Diperbarui']);
        $this->delete(route('siswa.destroy', $siswa))->assertRedirect(route('siswa.index'));

        $this->post(route('guru.store'), [
            'nip' => '2001',
            'nama' => 'Nama Guru',
            'jenis_kelamin' => 'P',
            'email' => 'guru@example.test',
        ])->assertRedirect(route('guru.index'));
        $guru = Guru::firstOrFail();
        $this->get(route('guru.index'))->assertOk();
        $this->get(route('guru.create'))->assertOk();
        $this->get(route('guru.edit', $guru))->assertOk();

        $this->put(route('guru.update', $guru), [
            'nip' => '2001',
            'nama' => 'Nama Guru Diperbarui',
            'jenis_kelamin' => 'P',
            'email' => 'guru@example.test',
        ])->assertRedirect(route('guru.index'));
        $this->assertDatabaseHas('gurus', ['id' => $guru->id, 'nama' => 'Nama Guru Diperbarui']);
        $this->delete(route('guru.destroy', $guru))->assertRedirect(route('guru.index'));

        $this->post(route('mapel.store'), [
            'kode_mapel' => 'MAT',
            'nama_mapel' => 'Matematika',
            'kkm' => 75,
        ])->assertRedirect(route('mapel.index'));
        $mapel = Mapel::firstOrFail();
        $this->get(route('mapel.index'))->assertOk();
        $this->get(route('mapel.create'))->assertOk();
        $this->get(route('mapel.edit', $mapel))->assertOk();

        $this->put(route('mapel.update', $mapel), [
            'kode_mapel' => 'MAT',
            'nama_mapel' => 'Matematika Lanjutan',
            'kkm' => 80,
        ])->assertRedirect(route('mapel.index'));
        $this->assertDatabaseHas('mapels', ['id' => $mapel->id, 'kkm' => 80]);
        $this->delete(route('mapel.destroy', $mapel))->assertRedirect(route('mapel.index'));

    }

    public function test_nilai_are_calculated_against_the_subject_kkm_and_duplicates_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $kelas = Kelas::create([
            'nama_kelas' => 'XI RPL 1',
            'tingkat' => 'XI',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2026/2027',
        ]);
        $siswa = Siswa::create([
            'nis' => '1001',
            'nama' => 'Nama Siswa',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
        ]);
        $mapel = Mapel::create([
            'kode_mapel' => 'MAT',
            'nama_mapel' => 'Matematika',
            'kkm' => 80,
        ]);

        $nilaiData = [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai_tugas' => 70,
            'nilai_uts' => 80,
            'nilai_uas' => 90,
        ];

        $this->post(route('nilai.store'), $nilaiData)
            ->assertRedirect(route('nilai.index'));
        $this->get(route('nilai.index'))->assertOk();
        $this->get(route('nilai.create'))->assertOk();

        $nilai = Nilai::firstOrFail();
        $this->get(route('nilai.edit', $nilai))->assertOk();
        $this->assertEquals(81.00, $nilai->nilai_akhir);
        $this->assertSame('Lulus', $nilai->keterangan);

        $this->put(route('nilai.update', $nilai), [
            ...$nilaiData,
            'nilai_tugas' => 50,
            'nilai_uts' => 50,
            'nilai_uas' => 50,
        ])->assertRedirect(route('nilai.index'));

        $this->assertDatabaseHas('nilais', [
            'id' => $nilai->id,
            'nilai_akhir' => 50,
            'keterangan' => 'Tidak Lulus',
        ]);

        $this->post(route('nilai.store'), $nilaiData)
            ->assertSessionHasErrors('siswa_id');
        $this->post(route('nilai.store'), [
            ...$nilaiData,
            'nilai_tugas' => 101,
        ])->assertSessionHasErrors('nilai_tugas');

        $this->delete(route('nilai.destroy', $nilai))->assertRedirect(route('nilai.index'));
        $this->assertDatabaseMissing('nilais', ['id' => $nilai->id]);
    }

    public function test_database_seeder_can_run_repeatedly_without_duplicating_records(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('kelas', 3);
        $this->assertDatabaseCount('siswas', 3);
        $this->assertDatabaseCount('gurus', 2);
        $this->assertDatabaseCount('mapels', 3);
        $this->assertDatabaseCount('nilais', 3);
        $this->assertDatabaseHas('nilais', [
            'siswa_id' => Siswa::where('nis', '2026001')->value('id'),
            'mapel_id' => Mapel::where('kode_mapel', 'MP-BASDAT')->value('id'),
            'nilai_akhir' => 90.40,
        ]);
    }
}
