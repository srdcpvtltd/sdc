<?php $__env->startSection('breadcrumb'); ?>
    <a class="breadcrumb-item" href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a><a class="breadcrumb-item"
        href="<?php echo e(route('users.index')); ?>"><?php echo e(__('User')); ?></a><span
        class="breadcrumb-item active"><?php echo e(__('Create')); ?></span>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo e(__('Create User')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .viewer_div .form-group .form-control.select2 {
            width: 100%;
            height: 38px;
            line-height: 1.5;
        }
        /* .select2 {
            width: 100% !important;
        } */
    </style>
    <?php echo Form::open(['route' => 'users.store', 'method' => 'POST']); ?>

    <div class="col-md-12 m-auto">
        <div class="card">
            <div class="card-header"><?php echo e(__('Create New User')); ?> </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-3">
                        <?php echo e(__('Role:')); ?>

                        <?php echo Form::select('roles', $roles, null, ['class' => 'form-control', 'id' => 'roles']); ?>

                    </div>
                    <div class="form-group col-md-3">
                        <?php echo e(__('Name:')); ?>

                        <?php echo Form::text('name', null, ['placeholder' => __('Hotel Name'), 'class' => 'form-control']); ?>

                    </div>
                    <div class="form-group col-md-3">
                        <?php echo e(__('Email:')); ?>

                        <?php echo Form::text('email', null, ['placeholder' => __('Email'), 'class' => 'form-control']); ?>

                    </div>
                    <div class="form-group col-md-3">
                        <?php echo e(__('Password:')); ?>

                        <?php echo Form::password('password', ['placeholder' => __('Password'), 'class' => 'form-control']); ?>

                    </div>
                    <div class="form-group col-md-3">
                        <?php echo e(__('Confirm Password:')); ?>

                        <?php echo Form::password('confirm-password', ['placeholder' => __('Confirm Password'), 'class' => 'form-control']); ?>

                    </div>
                </div>
                <div class="row viewer_div">
                    <div class="form-group col-md-3">
                        <label>Country:</label><br>
                        <select class="form-control select2" name="country_id" id="country">
                            <option value="" selected>Select country</option>
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>State:</label><br>
                        <select name="state_id" class="form-control select2" id="state">
                            <option value="">Select State</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>City:</label><br>
                        <select name="city_id" class="form-control select2" id="city">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Police Station:</label><br>
                        <select name="police_station_id" class="form-control select2" id="police_station_id">
                            <option value="">Select Police Station</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <?php echo e(Form::submit(__('Submit'), ['class' => 'btn btn-primary '])); ?>


                <a class="btn btn-secondary" href="<?php echo e(route('users.index')); ?>"> <?php echo e(__('Back')); ?></a>
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.viewer_div').hide();

        $('#roles').on('change', function() {
            var role = this.value;
            console.log(role);

            if (role == "viewer") {
                $('.viewer_div').show();
            } else {
                $('.viewer_div').hide();
            }
        });

        $('#country').on('change', function() {
            var countryId = this.value;
            $('#state').html('');
            $.ajax({
                url: "<?php echo e(route('getStates')); ?>?country_id=" + countryId,
                type: 'get',
                success: function(res) {
                    $('#state').html('<option value="">Select State</option>');
                    $.each(res, function(key, value) {
                        $('#state').append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                }
            });
        });
        $('#state').on('change', function() {
            var stateId = this.value;
            $('#city').html('');
            $.ajax({
                url: "<?php echo e(route('getCities')); ?>?state_id=" + stateId,
                type: 'get',
                success: function(res) {
                    $('#city').html('<option value="">Select City</option>');
                    $.each(res, function(key, value) {
                        $('#city').append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                }
            });
        });
        $('#city').on('change', function() {
            var cityId = this.value;
            $('#police_station_id').html('');
            $.ajax({
                url: "<?php echo e(route('getPolicestation')); ?>?city_id=" + cityId,
                type: 'get',
                success: function(res) {
                    $('#police_station_id').html(
                        '<option value="">Select Police Station</option>');
                    $.each(res, function(key, value) {
                        $('#police_station_id').append('<option value="' + value
                            .id + '">' + value.code + '</option>');
                    });
                }
            });
        });
    });
</script>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sarai\resources\views/users/create.blade.php ENDPATH**/ ?>