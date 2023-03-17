@extends('layouts.app')



<?php 

error_reporting(E_ALL ^ E_NOTICE); 
 ?>

@section('content')
        <div class="page-wrapper">

            <!-- ============================================================== -->

            <!-- Bread crumb and right sidebar toggle -->

            <!-- ============================================================== -->

            <div class="row page-titles">

                <div class="col-md-5 align-self-center">

                    <h3 class="text-themecolor">{{trans('lang.food_plural')}}</h3>

                </div>

                <div class="col-md-7 align-self-center">

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.food_plural')}}</li>
                    </ol>

                </div>

                <div>

                </div>

            </div>

      

            <div class="container-fluid">

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">
                                <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                    <li class="nav-item active">
                                      <a class="nav-link" href="{!! route('foods') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.food_table')}}</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" href="{!! route('foods.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.food_create')}}</a>
                                    </li>                                    
                                </ul>
                            </div>

                            <div class="card-body">

                            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">Processing...</div>

                                <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                            <div id="users-table_filter" class="pull-right"><label>{{trans('lang.search_by')}}
                                <select name="selected_search" id="selected_search" class="form-control input-sm">
                                    <option value="name">{{ trans('lang.name')}}</option>
                                    <option value="category">{{ trans('lang.food_category_id')}}</option>
                                </select>
                                <div class="form-group">
                                            <input type="search" id="search" class="search form-control"
                                                   placeholder="Search">
                                            <select id="category_search_dropdown" class="form-control">
                                                <option value="All">
                                                    Select Category
                                                </option>
                                            </select>
                                         


                                        </div>   
                                        <button onclick="searchtext();" class="btn btn-warning btn-flat">
                                        {{trans('lang.search')}}
                                    </button>&nbsp;<button onclick="searchclear();"
                                                           class="btn btn-warning btn-flat">
                                        {{trans('lang.clear')}}
                                    </button>                         
                                    </div>
 
                                <div class="table-responsive m-t-10">


                                    <table id="example24" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>
                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                            class="col-3 control-label" for="is_active">
                                        <a id="deleteAll" class="do_not_delete" href="javascript:void(0)"><i
                                                    class="fa fa-trash"></i> {{trans('lang.all')}}</a></label></th>
                                                <th>{{trans('lang.food_image')}}</th>
                                                <th>{{trans('lang.food_name')}}</th>
                                                <th>{{trans('lang.food_price')}}</th>
                                                <th>{{trans('lang.food_category_id')}}</th>
                                                <th>{{trans('lang.food_publish')}}</th>
                                                <th>{{trans('lang.actions')}}</th>
                                            </tr>

                                        </thead>

                                        <tbody id="append_list1">


                                        </tbody>

                                    </table>
                                                   <!--  <div class="dataTables_paginate paging_simple_numbers" id="data-table_paginate"><ul class="pagination"><li class="paginate_button previous" id="users-table_previous">
                <a href="javascript:void(0);" id="users_table_previous_btn" onclick="prev()" data-dt-idx="0" tabindex="0">Previous</a></li><li class="paginate_button">
                <a href="javascript:void(0);" id="users_table_next_btn" onclick="next()" aria-controls="users-table" data-dt-idx="2" tabindex="0">Next</a></li></ul></div> -->
                  <div id="data-table_paginate" style="display:none">
                                                      <nav aria-label="Page navigation example">
                                         <ul class="pagination justify-content-center">
                                            <li class="page-item ">
                                                <a class="page-link" href="javascript:void(0);" id="users_table_previous_btn" onclick="prev()"  data-dt-idx="0" tabindex="0">{{trans('lang.previous')}}</a>
                                            </li>
                                            <li class="page-item">
                                            <a class="page-link" href="javascript:void(0);" id="users_table_next_btn" onclick="next()"  data-dt-idx="2" tabindex="0">{{trans('lang.next')}}</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>



@endsection


@section('scripts')
   <script type="text/javascript">
    
    var database = firebase.firestore();
    var offest=1;
    var pagesize=10; 
    var end = null;
    var endarray=[];
    var start = null;
    var user_number = [];
    var vendorUserId = "<?php echo $id; ?>";
    var vendorId;
    var ref;
    var append_list = '';
    var placeholderImage = '';
    var activeCurrencyref = database.collection('currencies').where('isActive',"==",true);
    var activeCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;

    activeCurrencyref.get().then( async function(currencySnapshots){
      currencySnapshotsdata =  currencySnapshots.docs[0].data();
      activeCurrency = currencySnapshotsdata.symbol;
      currencyAtRight = currencySnapshotsdata.symbolAtRight;

      if (currencySnapshotsdata.decimal_degits) {
            decimal_degits = currencySnapshotsdata.decimal_degits;
        }
    })
    console.log('vendorUserId '+vendorUserId);
    getVendorId(vendorUserId).then(data => {
        vendorId= data;
        ref= database.collection('vendor_products').where('vendorID',"==",vendorId);
        $(document).ready(function() {
            $('#category_search_dropdown').hide();
            $(document.body).on('click', '.redirecttopage' ,function(){    
                var url=$(this).attr('data-url');
                window.location.href = url;
            }); 
            console.log(vendorId+' = vendorId');
            var inx= parseInt(offest) * parseInt(pagesize);
            jQuery("#data-table_processing").show();
            append_list = document.getElementById('append_list1');
            append_list.innerHTML='';

            var placeholder = database.collection('settings').doc('placeHolderImage');
            placeholder.get().then( async function(snapshotsimage){
                var placeholderImageData = snapshotsimage.data();
                placeholderImage = placeholderImageData.image;
            })

            ref.limit(pagesize).get().then( async function(snapshots){  
                html='';
                html=buildHTML(snapshots);
                if(html!=''){
                    append_list.innerHTML=html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                   disableClick();
                }
                 if(snapshots.docs.length < pagesize){ 
                    jQuery("#data-table_paginate").hide();
                }else{
                    jQuery("#data-table_paginate").show();
                }
                
                jQuery("#data-table_processing").hide();
            }); 
 
        });

    })
    $(document.body).on('change', '#selected_search', function () {

 if (jQuery(this).val() == 'category') {
   
        var ref_category = database.collection('vendor_categories');
    
    ref_category.get().then(async function (snapshots) {
        snapshots.docs.forEach((listval) => {
            var data = listval.data();
            $('#category_search_dropdown').append($("<option></option").attr("value", data.id).text(data.title));

        });

    });
    jQuery('#search').hide();
    jQuery('#category_search_dropdown').show();
} else {
    jQuery('#search').show();
    jQuery('#category_search_dropdown').hide();

}
});
    function buildHTML(snapshots){
        var html='';
        var alldata=[];
        var number= [];
        snapshots.docs.forEach((listval) => {
            var datas=listval.data();
            datas.id=listval.id;
            alldata.push(datas);
        });
                

        //     alldata.sort(function(a, b) {
                
        //       var keyA = a.createdAt.seconds,
        //         keyB = b.createdAt.seconds;
                
        //       if (keyA < keyB) return -1;
        //       if (keyA > keyB) return 1;
        //       return 0;
        // });
        var count = 0;
        alldata.forEach((listval) => {
            
            var val=listval;
            
                html=html+'<tr>';
                newdate='';

                var id = val.id;
                console.log(val.vendorID);
                var route1 =  '{{route("foods.edit",":id")}}';
                route1 = route1.replace(':id', id);
                html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';
                if(val.photo == ''){     
                      html=html+'<td><img class="rounded" style="width:50px" src="'+placeholderImage+'" alt="image"></td>';
                }else{
                    html=html+'<td><img class="rounded" style="width:50px" src="'+val.photo+'" alt="image"></td>';
                }

                // html=html+'<td><a href="'+route1+'">'+val.name+'</a></td>';
                html=html+'<td data-url="'+route1+'" class="redirecttopage">'+val.name+'</td>';
                if(val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0' ){
                    if (currencyAtRight) {

                    html = html + '<td>' + parseFloat(val.disPrice).toFixed(decimal_degits) + '' + activeCurrency + ' <s>' + parseFloat(val.price).toFixed(decimal_degits) + '' + activeCurrency + '</s></td>';
                    } else {
                    html = html + '<td>' + activeCurrency + '' + parseFloat(val.disPrice).toFixed(decimal_degits) + ' <s>' + activeCurrency + '' + parseFloat(val.price).toFixed(decimal_degits) + '</s></td>';
                    }
                    } else {

                    if (currencyAtRight) {
                    html = html + '<td>' + parseFloat(val.price).toFixed(decimal_degits) + '' + activeCurrency + '</td>';
                    } else {
                    html = html + '<td>' + activeCurrency + '' + parseFloat(val.price).toFixed(decimal_degits) + '</td>';
                    }
                    }

                const category = productCategory(val.categoryID);
                html=html+'<td class="category_'+val.categoryID+'"></td>';
                
                if (val.publish) {
                html = html + '<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="publish"><span class="slider round"></span></label></td>';
            } else {
                html = html + '<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="publish"><span class="slider round"></span></label></td>';
            }
                html=html+'<td class="action-btn"><a href="'+route1+'"><i class="fa fa-edit"></i></a><a id="'+val.id+'" class="do_not_delete" name="food-delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a></td>';

                html=html+'</tr>';
                count =count +1;
          });
          return html;      
}
/* toggal publish action code start*/
$(document).on("click","input[name='publish']",function(e){
                var ischeck=$(this).is(':checked');
                var id=this.id;
                if(ischeck){
                database.collection('vendor_products').doc(id).update({'publish': true}).then(function (result) {

                });
                }else{
                database.collection('vendor_products').doc(id).update({'publish': false}).then(function (result) {

                });
                }

            });

    /*toggal publish action code end*/
$("#is_active").click(function () {
        $("#example24 .is_open").prop('checked', $(this).prop('checked'));

    });
    $("#deleteAll").click(function () {
        if ($('#example24 .is_open:checked').length) {
            if (confirm('Are You Sure want to Delete Selected Data ?')) {
                jQuery("#data-table_processing").show();
                $('#example24 .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    console.log(dataId);
                    database.collection('vendor_products').doc(dataId).delete().then(function () {
                        window.location.reload();

                    });

                });

            }
        } else {
            alert('Please Select Any One Record .');
        }
    });


async function productCategory(category) {
var productCategory='';
await database.collection('vendor_categories').where("id","==",category).get().then( async function(snapshotss){
  
            if(snapshotss.docs[0]){
                var category_data = snapshotss.docs[0].data();
                productCategory = category_data.title;
                console.log(productCategory);
                jQuery(".category_"+category).html(productCategory);
            }else{
                jQuery(".category_"+category).html('');
            } 
});
return productCategory;
} 
function prev(){
    if(endarray.length==1){
        return false;
    }
    end=endarray[endarray.length-2];
    console.log(endarray);
  
  if(end!=undefined || end!=null){
            jQuery("#data-table_processing").show();
                 if(jQuery("#selected_search").val()=='name' && jQuery("#search").val().trim()!=''){

                    listener=ref.orderBy('name').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val()+'\uf8ff').startAt(end).get();
                }else if (jQuery("#selected_search").val() == 'category' && jQuery("#category_search_dropdown").val().trim() != '') {

                if (jQuery("#category_search_dropdown").val() == "All") {
                    listener = ref.limit(pagesize).startAt(end).get();
                } else {
                    listener = ref.orderBy('categoryID').limit(pagesize).startAt(jQuery("#category_search_dropdown").val()).endAt(jQuery("#category_search_dropdown").val() + '\uf8ff').startAt(end).get();

                }

                listener.then((snapshots) => {
                    html = '';
                    html = buildHTML(snapshots);
                    jQuery("#data-table_processing").hide();
                    if (html != '') {
                        append_list.innerHTML = html;
                        start = snapshots.docs[snapshots.docs.length - 1];

                        endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);

                        if (snapshots.docs.length < pagesize) {

                            jQuery("#users_table_previous_btn").hide();
                        }

                    }
                });

                }
                
                else{
                    listener = ref.startAt(end).limit(pagesize).get();
                }
                
                listener.then((snapshots) => {
                html='';
                html=buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if(html!=''){
                    append_list.innerHTML=html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    console.log(start);
                    endarray.splice(endarray.indexOf(endarray[endarray.length-1]),1);

                    if(snapshots.docs.length < pagesize){ 
   
                        jQuery("#users_table_previous_btn").hide();
                    }
                    
                }
            });
  }
}


function next(){
  if(start!=undefined || start!=null){

        jQuery("#data-table_processing").hide();
          // listener = ref.startAfter(start).limit(pagesize).get();

          if(jQuery("#selected_search").val()=='name' && jQuery("#search").val().trim()!=''){

                listener=ref.orderBy('name').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val()+'\uf8ff').startAfter(start).get();
            }else if(jQuery("#selected_search").val() == 'category' && jQuery("#category_search_dropdown").val().trim() != ''){
                if (jQuery("#category_search_dropdown").val() == "All") {
                    listener = ref.limit(pagesize).startAt(end).get();
                } else {
                    listener = ref.orderBy('categoryID').limit(pagesize).startAt(jQuery("#category_search_dropdown").val()).endAt(jQuery("#category_search_dropdown").val() + '\uf8ff').startAt(end).get();

                }

                listener.then((snapshots) => {
                    html = '';
                    html = buildHTML(snapshots);
                    jQuery("#data-table_processing").hide();
                    if (html != '') {
                        append_list.innerHTML = html;
                        start = snapshots.docs[snapshots.docs.length - 1];

                        endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);

                        if (snapshots.docs.length < pagesize) {

                            jQuery("#users_table_previous_btn").hide();
                        }

                    }
                });
            }
            else{
                listener = ref.startAfter(start).limit(pagesize).get();
            }
          listener.then((snapshots) => {
            
                html='';
                html=buildHTML(snapshots);

                jQuery("#data-table_processing").hide();
                if(html!=''){
                    append_list.innerHTML=html;
                    start = snapshots.docs[snapshots.docs.length - 1];

                    if(endarray.indexOf(snapshots.docs[0])!=-1){
                        endarray.splice(endarray.indexOf(snapshots.docs[0]),1);
                    }
                    endarray.push(snapshots.docs[0]);
                }
            });
    }
}

function searchclear(){
    jQuery("#search").val('');
    jQuery("#category_search_dropdown").val('All');
    searchtext();
}

function searchtext(){

  var offest=1;
 /* var pagesize=5;
  var start = null;
  var end = null;
  var endarray=[];
  var inx= parseInt(offest) * parseInt(pagesize); */
  jQuery("#data-table_processing").show();
  
  append_list.innerHTML='';  

   if(jQuery("#selected_search").val()=='name' && jQuery("#search").val().trim()!=''){

     wherequery=ref.orderBy('name').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val()+'\uf8ff').get();

   }else if(jQuery("#selected_search").val() == 'category' && jQuery("#category_search_dropdown").val().trim() != ''){
    if (jQuery("#category_search_dropdown").val() == "All") {
                wherequery = ref.limit(pagesize).get();
            } else {
                wherequery = ref.orderBy('categoryID').limit(pagesize).startAt(jQuery("#category_search_dropdown").val()).endAt(jQuery("#category_search_dropdown").val() + '\uf8ff').get();

            }

                // wherequery.then((snapshots) => {
                //     html = '';
                //     html = buildHTML(snapshots);
                //     jQuery("#data-table_processing").hide();
                //     if (html != '') {
                //         append_list.innerHTML = html;
                //         start = snapshots.docs[snapshots.docs.length - 1];

                //         endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);

                //         if (snapshots.docs.length < pagesize) {

                //             jQuery("#users_table_previous_btn").hide();
                //         }

                //     }
                // });
            } else{

    wherequery=ref.limit(pagesize).get();
  }
  
  wherequery.then((snapshots) => {
    html='';
    html=buildHTML(snapshots);
    jQuery("#data-table_processing").hide();
    if(html!=''){
        append_list.innerHTML=html;
        start = snapshots.docs[snapshots.docs.length - 1];

        endarray.push(snapshots.docs[0]);
        /*if(snapshots.docs.length<pagesize && jQuery("#selected_search").val().trim()!='' && jQuery("#search").val().trim()!=''){*/
        if(snapshots.docs.length < pagesize){ 
   
            jQuery("#data-table_paginate").hide();
        }else{

            jQuery("#data-table_paginate").show();
        }
    }
}); 

}

$(document).on("click","a[name='food-delete']", function (e) {
        var id = this.id;
       
            //    var is_disable_delete = "<?php echo env('IS_DISABLE_DELETE', 0); ?>";
            //     if(is_disable_delete == 1){
            //         alert("This is for demo, We can't allow to delete");
            //         return false;
            //     }
     database.collection('vendor_products').doc(id).delete().then(function(result){
        window.location.href = '{{ url()->current() }}';
    });  



});


async function getVendorId(vendorUser){
    var vendorId = '';
    var ref;
    await database.collection('vendors').where('author',"==",vendorUser).get().then(async function(vendorSnapshots){
        var vendorData = vendorSnapshots.docs[0].data();    
        vendorId = vendorData.id;
    })
    
            return vendorId;
}

function disableClick(){
    var is_disable_delete = "<?php echo env('IS_DISABLE_DELETE'); ?>";
    if(is_disable_delete == 1){
        jQuery("a.do_not_delete").removeAttr("name");
        jQuery("a.do_not_delete").attr("name","alert_demo");       
    }
}


$(document).on("click","a[name='alert_demo']", function (e) {
    
    alert(doNotDeleteAlert);
}); 

</script>


@endsection
