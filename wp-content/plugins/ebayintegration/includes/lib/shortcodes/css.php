<style>

    .shortcode_tab_box .user_sku_list li{ 
        clear: both;
        display: block;
        line-height: 1em;
        height: auto;

    }

    .shortcode_tab_box {
        box-shadow: 0px 2px 18px 0px rgba(0,0,0,0.3);
    }

    .shortcode_tab_box H1 {
        color: black;
    }
    
    .shortcode_tab_box ul {
        list-style-type: none;
        padding: 0 !important;
        line-height: inherit !important;        
        background-color: #f4f4f4;
        height: auto !important;
        word-wrap: break-word;
        box-sizing: border-box;
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .shortcode_tab_box li {
        float: left;
        border-right: none;
        border-bottom: none;
        display: table;
        height: 72px;
        position: relative;
        line-height: 4em;
        font-weight: bold;
    }
    .shortcode_tab_box li a {
        text-decoration: none;
        padding: 4px 30px;
        vertical-align: middle;
        display: table-cell;
        color: #666;
    }

    .shortcode_tab_box li.active {
        background-color: #fff;
        font-weight: bold;
        border-bottom: none;

    }

</style>

<?php 

function Activate($page){
    if( isset( $_GET['mode']) == false ){
        if( $page == "new" ){
            return "active";
        }
    } 
    else {
        if( $page == $_GET['mode'] ){
            return "active";
        } else {
            return "";
        }
    }
}

function ActivateListing($page){
    if( isset( $_GET['mode']) == false ){
        if( $page == "auction_items" ){
            return "active";
        }
    } 
    else {
        if( $page == $_GET['mode'] ){
            return "active";
        } else {
            return "";
        }
    }
}

function ActivateGrading($page){
    if( isset( $_GET['mode']) == false ){
        if( $page == "open" ){
            return "active";
        }
    } 
    else {
        if( $page == $_GET['mode'] ){
            return "active";
        } else {
            return "";
        }
    }
}

function AdministratorGrading($page){
    if( isset( $_GET['mode']) == false ){
        if( $page == "receiving" ){
            return "active";
        }
    } 
    else {
        if( $page == $_GET['mode'] ){
            return "active";
        } else {
            return "";
        }
    }
}

function AdministratorEbay($page){
    if( isset( $_GET['mode']) == false ){
        if( $page == "auction" ){
            return "active";
        }
    } 
    else {
        if( $page == $_GET['mode'] ){
            return "active";
        } else {
            return "";
        }
    }
}

function AdministratorMembers($page){
    if( isset( $_GET['mode']) == false ){
        if( $page == "users" ){
            return "active";
        }
    } 
    else {
        if( $page == $_GET['mode'] ){
            return "active";
        } else {
            return "";
        }
    }
}



?>