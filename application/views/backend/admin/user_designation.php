<?php
    if($this->session->flashdata('club_updated'))
    {
        echo '<div align="center">
         <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          '.$this->session->flashdata('club_updated').'
         </div> 
        </div>';
    }
?>
<script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>

<style>
    .green{
        color: green !important;
    }
    .orange{
        color: orange !important;
    }
    .fld {
        position: absolute;
        right: 30px;
        font-size: 11px;
    }

    .fle {
        position: absolute;
        right: 68px;
        padding-right: 11px;
        font-size: 11px;
    }

    .fle .entypo-pencil {
        display: inline !important;
    }

    .fld .entypo-trash {
        display: inline !important;
        padding-right: 0px;
        margin-right: -5px;
    }

    @media (max-width:400px) {
        .fle {
            position: static;
        }
        .fld {
            position: static;
        }
    }

    .inli:hover {
        background: #b50000;
    }

    .tree,
    .tree ul {
        margin: 0;
        padding: 0;
        list-style: none;
        cursor: pointer;
    }

    .tree ul {
        margin-left: 1em;
        position: relative
    }

    .tree ul ul {
        margin-left: .5em
    }

    .tree ul:before {
        content: "";
        display: block;
        width: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        border-left: 1px solid
    }

    .tree li {
        margin: 0;
        padding: 0px 0px 0px 1em;
        line-height: 2em;
        color: #a2a4a8;
        position: relative;
        background:white;
        border-left:4px solid #012b3c;
    }

    .tree li:hover {
        background-color: rgba(204, 204, 204, 0.08);
    }

    .cospan:hover {
        border-right: 3px solid #ad0707;
    }

    .tree li a {
        text-decoration: none;
        color: #7d8086;
    }

    .tree ul li:before {
        content: "";
        display: block;
        width: 10px;
        height: 0;
        border-top: 1px solid;
        margin-top: -1px;
        position: absolute;
        top: 1em;
        left: 0
    }

    .tree ul li:last-child:before {
        background: #fff;
        height: auto;
        top: 1em;
        bottom: 0
    }

    .indicator {
        margin-right: 5px;
    }

    .tree li button,
    .tree li button:active,
    .tree li button:focus {
        text-decoration: none;
        color: #369;
        border: none;
        background: transparent;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px;
        outline: 0;
    }

    .coact {
        text-transform: capitalize;
        padding-left: 7px;
        background-color: #EEE;
        margin-left: 10px;
        padding-bottom: 6px;
        color: #000;
        margin-right: 18px;
        padding-top: 5px;
        font-size: 12px;
    }

    .myarrow {
        font-size: 20px;
        padding-left: 7px;
        padding-right: 6px;
        position: relative;
        top: 3px;
    }

    .coabg:hover {
        color: red;
    }

    .active {
        color: red !important;
    }
    ul#tree3 li {
        font-size: 16px;
        border-bottom: 1px solid #0000001c;
        color: black;
    }
    </style>
    
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('designation'); ?>
            </h3>
        </div>
         <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <?php
            if (right_granted('designation_manage'))
            {
            ?>
                <a data-step="1" data-position='left' data-intro="press this button open popup add new designation" style="margin-right: 10px;" class="btn btn-primary pull-right" id="myBtn" href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_add_edit_designation/');">
                 <i class=" white entypo-plus-circled" style="color:#FFF !important"></i>
                 <?php echo get_phrase('add');?>
                </a>
            <?php
            }
            ?>
        </div>
    </div>
    


    <div class="row" data-step="2" data-position='top' data-intro="designation records & you have option edit / delete">
        
    <?php
        foreach($designation_data as $designation){
            echo $designation;
        }
    ?>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($)
    {
    $(".dataTables_wrapper select").select2(
        {
            minimumResultsForSearch: -1
        });
    });
    </script>
    <script>
    $.fn.extend({
        treed: function(o) {

            var openedClass = 'glyphicon-minus-sign';
            var closedClass = 'glyphicon-plus-sign';

            if (typeof o != 'undefined') {
                if (typeof o.openedClass != 'undefined') {
                    openedClass = o.openedClass;
                }
                if (typeof o.closedClass != 'undefined') {
                    closedClass = o.closedClass;
                }
            };

            //initialize each of the top levels
            var tree = $(this);
            tree.addClass("tree");
            tree.find('li').has("ul").each(function() {
                var branch = $(this); //li with children ul
                branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
                branch.addClass('branch');
                branch.on('click', function(e) {
                    if (this == e.target) {
                        var icon = $(this).children('i:first');
                        icon.toggleClass(openedClass + " " + closedClass);
                        $(this).children().children().toggle();
                    }
                })
                branch.children().children().toggle();
            });
            //fire event from the dynamically added icon
            tree.find('.branch .indicator').each(function() {
                $(this).on('click', function() {
                    $(this).closest('li').click();
                });
            });
            //fire event to open branch if the li contains an anchor instead of text
            tree.find('.branch>a').each(function() {
                $(this).on('click', function(e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
            //fire event to open branch if the li contains a button instead of text
            tree.find('.branch>button').each(function() {
                $(this).on('click', function(e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
        }
    });

    //Initialization of treeviews

    $('#tree1').treed();

    $('#tree2').treed({
        openedClass: 'glyphicon-folder-open',
        closedClass: 'glyphicon-folder-close'
    });

    $('#tree3').treed({
        openedClass: 'glyphicon-chevron-right',
        closedClass: 'glyphicon-chevron-down'
    });

    $('#tree4').treed({
        openedClass: 'glyphicon-chevron-right',
        closedClass: 'glyphicon-chevron-down'
    });
    $('#tree5').treed({
        openedClass: 'glyphicon-chevron-right',
        closedClass: 'glyphicon-chevron-down'
    });
    $('#tree6').treed({
        openedClass: 'glyphicon-chevron-right',
        closedClass: 'glyphicon-chevron-down'
    });
    $('#tree7').treed({
        openedClass: 'glyphicon-chevron-right',
        closedClass: 'glyphicon-chevron-down'
    });
    </script>
