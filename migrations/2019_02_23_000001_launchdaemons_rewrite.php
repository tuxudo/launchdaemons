<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class LaunchdaemonsRewrite extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        
        // Need to kill the old LaunchDaemon table
        $capsule::schema()->dropIfExists('launchdaemons');
        
        $capsule::schema()->create('launchdaemons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('label')->nullable();
            $table->text('path')->nullable();
            $table->boolean('disabled')->nullable();
            $table->boolean('ondemand')->nullable();
            $table->boolean('runatload')->nullable();
            $table->text('program')->nullable();
            $table->boolean('startonmount')->nullable();
            $table->integer('startinterval')->nullable();
            $table->boolean('keepalive')->nullable();
            $table->text('daemon_json')->nullable();
            
            // Create indexes
            $table->index('serial_number');
            $table->index('label');
            $table->index('disabled');
            $table->index('ondemand');
            $table->index('runatload');
            $table->index('startonmount');
            $table->index('startinterval');
            $table->index('keepalive');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('launchdaemons');
    }
}
