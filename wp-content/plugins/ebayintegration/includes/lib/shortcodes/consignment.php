<style>

    @media (max-width: 479px) {
        body {
            background-color: black !important;
        }
    }
    @media (max-width: 767px) {
        body {
            background-color: black !important;
        }
    }

    .shortcode_tab_box {
        box-shadow: 0px 2px 18px 0px rgba(0,0,0,0.3);
    }
    
    .shortcode_tab_box ul {
        list-style-type: none;
        padding: 0 !important;
        line-height: inherit !important;        
        background-color: #f4f4f4;
        border-bottom: none;
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
        border-bottom: 1px solid #d9d9d9;
        display: table;
        height: 72px;
        position: relative;
        line-height: 4em;
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
    }

</style>


  
<div class="shortcode_tab_box">
    <ul class="clearfix hidden-md">
        <li class="">
            <a class="" href="#">Much longer nav link</a>
        </li>
        <li class="active">
            <a class="" aria-current="page" href="#">Active</a>
        </li>
        <li class="">
            <a class="" href="#">Link</a>
        </li>
        <li class="">
            <a class="disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
    </ul>
    <div class="content">
        
    </div>
</div>