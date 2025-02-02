use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('nik')->change(); // Ubah model_id ke nik
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->string('nik')->change(); // Ubah model_id ke nik
        });
    }

    public function down()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change(); // Kembalikan ke default
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change(); // Kembalikan ke default
        });
    }
};
