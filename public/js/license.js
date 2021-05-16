// Call the dataTables jQuery plugin
$(function() {
    // on modal show
    $('#edit-modal').on('show.bs.modal', function() {
        var el = $(".edit-item-trigger-clicked"); // See how its usefull right here?
        var row = el.closest(".data-row");

        // get the data
        var agent = row.children(".agent").text();
        var date = new Date(row.children(".expire-date").text());
        var expire_date = date.getFullYear() +'-' +("0" + (date.getMonth() + 1)).slice(-2) +'-' + ("0" + date.getDate()).slice(-2);

        // var url = "{{route('system-licenses.update','id')}}";
        // url = url.replace('id',el.data('item-id'));
        var url = "/system-licenses/"+el.data('item-id');
        // fill the data in the input fields
        $("#edit-form").attr("action",url);
        $("#agent").val(agent);
        $("#expire_date").val(expire_date);
        $("#customer_name").html(row.children(".customer_name").text());
        $("#mac_address").html(row.children(".mac_address").text());
        $("#linux_info").html(row.children(".linux_info").text());
    });
    $("#serverSideDataTable").DataTable({
        "processing" :true,
        "serverSide" :true,
        "columnDefs" : [{
            'orderable' : false, targets: -1
        }],
        "ajax" : {
            "url" : "/all-licenses",
            "dataType" : "json",
            "type" : "POST",
            "data" : {_token: csrftoken}
        },
        "columns" : [
            {"data" : "id"},
            {"data" : "agent", 'class': "agent"},
            {"data" : "customer_name", 'class': "customer_name", "orderable": false},
            {"data" : "status"},
            {"data" : "expire_date", 'class':"expire-date"},
            {"data" : "mac_address", 'class':"mac_address  d-none"},
            {"data" : "linux_info", 'class':"linux_info d-none"},
            {"data" : "last_validate", 'class':""},
            {"data" : "last_validate_ip", 'class':""},
            {"data" : "actions", 'class': "action-col","orderable": false},
        ],
        "createdRow": function(row, data,dataIndex) {
            $(row).addClass('data-row');
        },
        "fnDrawCallback" : function() {
            $('.btn-switch').bootstrapToggle();
        }
    });
});
function changeStatus(id,ele) {
    if($(ele).is(":checked")) {
        var status = 'enabled';
    } else {
        var  status = 'disabled';
    }
    var url = "/system-licenses/"+id;
    $.ajax({
        type: "PATCH",
        url : url,
        data : {
            "_token" : csrftoken,
            'status' : status
        },
        success: function (json) {
            $.toast({
                text : json.message,
                position : 'top-right',
                showHideTransition: 'slide'
            });
        }
    });
}

function identityUpload(id,customer_id) {
    var options = {
        'backdrop': 'static'
    };
    var url = "/identity-upload/"+id;
    $("#upload_customer_id").val(customer_id);
    // fill the data in the input fields
    $("#uploadForm").attr("action",url);
    $('#uploadModal').modal(options);
}

function identityUploadOut() {
    var options = {
        'backdrop': 'static'
    };
    var url = "/licenseservice/getLicenseStatus";
    // $("#upload_customer_id").val(customer_id);
    // fill the data in the input fields
    $("#uploadForm").attr("action",url);
    $('#uploadModal').modal(options);
}

