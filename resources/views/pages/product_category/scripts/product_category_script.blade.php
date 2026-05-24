<script type="text/javascript">

/*==================================================
BANK DETAILS MODULE
==================================================*/

$(document).ready(function ()
{
    initializeProductCategoryDetailsTable();

    bindProductCategoryDetailsEvents();
});


/*==================================================
DATATABLE
==================================================*/

let ProductCategoryDetailsTable;

function initializeProductCategoryDetailsTable()
{
    ProductCategoryDetailsTable =
        $('#getProductCategoryDetailsList').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,
            scrollY: '500px',
            stateSave: true,

            ajax:
                "{{ route('getProductCategoryDetailsList') }}",

            columns: [

                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                { data: 'category_name' },

                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }

            ],

            order: [[1, 'asc']]

        });

    autoAdjustColumns(ProductCategoryDetailsTable);
}


/*==================================================
AUTO ADJUST TABLE
==================================================*/

function autoAdjustColumns(table)
{
    const container =
        table.table().container();

    const resizeObserver =
        new ResizeObserver(function ()
        {
            table.columns.adjust();
        });

    resizeObserver.observe(container);
}


/*==================================================
EVENT BINDINGS
==================================================*/

function bindProductCategoryDetailsEvents()
{
    /*
    CREATE
    */
    $('body').on(
        'click',
        '#createProductCategoryDetails',
        openCreateBankModal
    );

    /*
    EDIT
    */
    $('body').on(
        'click',
        '#editProductCategoryDetails',
        openEditProductCategoryModal
    );

    /*
    SAVE / UPDATE
    */
    $('#save-category').on(
        'click',
        saveProductCategoryDetails
    );

    /*
    DELETE MODAL
    */
    $('body').on(
        'click',
        '#deleteProductCategoryDetails',
        showDeleteCategoryModal
    );

    /*
    DELETE CONFIRM
    */
    $('body').on(
        'click',
        '#deleteProductCategoryDetailsConfirmed',
        deleteProductCategoryDetailsConfirmed
    );
}


/*==================================================
CREATE MODAL
==================================================*/

function openCreateBankModal()
{
    resetProductCategoryForm();

    $('#category_id').val('');

    $('#product_category_modal_title')
        .text('Create Product Category Details');
	
	$('#reset-category-form').show();
	
    $('#save-category')
        .html(`
            <i class="bi bi-save-fill me-2"></i>
            Save Product Category Details
        `);

    $('#ProductCategoryDetailsModal')
        .modal('show');
}

/*==================================================
RESET MODAL ON CLOSE
==================================================*/

$('#ProductCategoryDetailsModal').on('hidden.bs.modal', function ()
{
    /*
    RESET FORM
    */
    resetProductCategoryForm();

    /*
    RESET TITLE
    */
    $('#product_category_modal_title')
        .text('Create Product Category Details');

    /*
    RESET BUTTON
    */
    $('#save-category')
        .html(`
            <i class="bi bi-save-fill me-2"></i>
            Save Product Category Details
        `);

    /*
    SHOW RESET BUTTON
    */
    $('#reset-category-form')
        .show();
});

/*==================================================
EDIT MODAL
==================================================*/

function openEditProductCategoryModal()
{
    const category_id =
        $(this).data('id');

    resetProductCategoryForm();
	
	$('#reset-category-form').hide();
	
    $('#loading_data')
        .show();

    $('#ProductCategoryDetailsModal')
        .modal('show');

    $.ajax({

        url: '/product_category_info',

        type: 'POST',

        data: {

            category_id: category_id,

            _token:
                "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            const data =
                response.data ?? response;

            if(!data)
            {
                showDangerMessage(
                    'Product Category details not found.'
                );

                return;
            }

            /*
            LOAD VALUES
            */
            $('#category_id')
                .val(data.category_id);

            $('#category_name')
                .val(data.category_name);

           

            /*
            UPDATE MODAL
            */
            $('#product_category_modal_title')
                .text('Update Product Category Details');

            $('#save-category')
                .html(`
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Update Product Category Details
                `);
			
			
			
        },

        complete: function()
        {
            $('#loading_data')
                .hide();
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to load Product Category details.'
            );
        }

    });
}


/*==================================================
SAVE / UPDATE
==================================================*/

function saveProductCategoryDetails(event)
{
    event.preventDefault();

    const form =
        $('#ProductCategoryDetailsForm');

    form.addClass('was-validated');

    const category_id =
        $('#category_id').val();

    const payload = {

        category_id:
            category_id,

        category_name:
            $('#category_name').val(),

        _token:
            "{{ csrf_token() }}"
    };

    $.ajax({

        url:
            category_id
            ? '/update_product_category_post'
            : '/create_product_category_post',

        type: 'POST',

        data: payload,

        beforeSend: function()
        {
            setButtonLoading(
                '#save-category',
                true
            );
        },

        success: function(response)
        {
            console.log(response);

            $('#ProductCategoryDetailsModal')
                .modal('hide');

            reloadProductCategoryDetailsTable();

			showSuccessModal(response.success);
			
            resetProductCategoryForm();
			
			$('#product_category_modal_title')
				.text('Create Product CategoryDetails');

			$('#save-category')
				.html(`
					<i class="bi bi-save-fill me-2"></i>
					Save Product CategoryDetails
				`);
	
        },

        complete: function()
        {
            setButtonLoading(
                '#save-category',
                false
            );
        },

        error: function(xhr)
        {
            console.log(xhr);

            handleProductCategoryValidation(xhr);
        }

    });
}

function showSuccessModal(message)
{
    /*
    SET MESSAGE
    */
    $('#success_modal_message')
        .text(message);

    /*
    SHOW MODAL
    */
    $('#SuccessModal')
        .modal('show');

    /*
    AUTO CLOSE
    */
    setTimeout(function ()
    {
        $('#SuccessModal')
            .modal('hide');

    }, 1500);
}

/*==================================================
SHOW DELETE MODAL
==================================================*/

function showDeleteCategoryModal(event)
{
    event.preventDefault();

    const category_id =
        $(this).data('id');

    resetDeleteCategoryModal();

    $('#ProductCategoryDetailsDeleteModal')
        .modal('show');

    $('#deleteProductCategoryDetailsConfirmed')
        .prop('disabled', true);

    $.ajax({

        url: '/product_category_info',

        type: 'POST',

        data: {

            category_id: category_id,

            _token:
                "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            const data =
                response.data ?? response;

            if(!data)
            {
                showDangerMessage(
                    'Product Category details not found.'
                );

                $('#ProductCategoryDetailsDeleteModal')
                    .modal('hide');

                return;
            }

            /*
            SET BUTTON VALUE
            */
            $('#deleteProductCategoryDetailsConfirmed')
                .val(data.category_id);

            /*
            LOAD DETAILS
            */
            $('#confirm_delete_category_name')
                .text(data.category_name || '-');

            /*
            ENABLE BUTTON
            */
            $('#deleteProductCategoryDetailsConfirmed')
                .prop('disabled', false);
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to load Product Category details.'
            );

            $('#ProductCategoryDetailsDeleteModal')
                .modal('hide');
        }

    });
}


/*==================================================
DELETE CONFIRMED
==================================================*/

function deleteProductCategoryDetailsConfirmed()
{
    const category_id =
        $('#deleteProductCategoryDetailsConfirmed')
            .val();

    $('#deleteProductCategoryDetailsConfirmed')
        .prop('disabled', true);

    $.ajax({

        url: '/delete_product_category_confirmed',

        type: 'POST',

        data: {

            category_id: category_id,

            _token:
                "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            $('#ProductCategoryDetailsDeleteModal')
                .modal('hide');

            reloadProductCategoryDetailsTable();

            showDangerMessage(
                'Product Category Details Deleted'
            );
        },

        complete: function()
        {
            $('#deleteProductCategoryDetailsConfirmed')
                .prop('disabled', false);
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to delete Product Category details.'
            );
        }

    });
}


/*==================================================
HELPERS
==================================================*/

function reloadProductCategoryDetailsTable()
{
    ProductCategoryDetailsTable
        .ajax
        .reload(null, false);
}


function resetProductCategoryForm()
{
    $('#ProductCategoryDetailsForm')[0]
        .reset();

    $('#category_id')
        .val('');

    $('.invalid-feedback')
        .html('');

    $('#ProductCategoryDetailsForm')
        .removeClass('was-validated');
}


function resetDeleteCategoryModal()
{
    $('#confirm_delete_category_name')
        .text('-');

    $('#deleteProductCategoryDetailsConfirmed')
        .val('');
}


function setButtonLoading(button, loading)
{
    $(button).prop('disabled', loading);

    if (loading)
    {
        $('#loading_data')
            .show();
    }
    else
    {
        $('#loading_data')
            .hide();
    }
}

function showDangerMessage(message)
{
    $('#switch_notice_off').show();

    $('#sw_off').html(message);

    setTimeout(function ()
    {
        $('#switch_notice_off')
            .fadeOut('slow');

    }, 1000);
}

/*==================================================
VALIDATION ERROR MODAL
==================================================*/

function showValidationErrorModal(message)
{
    $('#validation_error_message')
        .text(message);

    $('#ValidationErrorModal')
        .modal('show');
}

function handleProductCategoryValidation(xhr)
{
    const errors =
        xhr.responseJSON.errors;

    /*
    CATEGORY NAME
    */
    if(errors.category_name)
    {
        $('#category_name_error')
            .html(errors.category_name[0])
            .show();
    }

}



</script>