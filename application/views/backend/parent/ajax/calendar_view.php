<style>

.week_name {
    background: #1a7866; 
    color: #fff; 
}  
#calendar_view_container table th{
    padding-bottom: 30px;
}
#calendar_view_container .highlight_day {
    background-color: #1a7866;
}
#calendar_view_container table td { 
    font-size: 16px;
    font-weight: 600;
    border: 2px solid #1a786638;
    
} 
#calendar_view_container table td ul.latest_event {
    font-size: 13px;
    padding: 0;
    margin-top: 5px;
    margin-bottom: 0;
    width: auto;
    font-weight: 600;
}
#calendar_view_container table td li span {
    margin-left: 6px;
    font-weight: 600;
    color: #1a7866;
    font-size: 14px;
}
</style>


<script type="text/javascript">
    $(function () {

        $('[data-toggle="tooltip"]').tooltip();
        //$('#example_test').popover(options);
    })
</script>
