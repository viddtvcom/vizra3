<script type="text/javascript" src="{$vurl}/js/tiny_mce/jquery.tinymce.js"></script>
{literal}
    <script type="text/javascript">


        $(document).ready(function () {
            $('textarea').tinymce({
                script_url: v_url + '/js/tiny_mce/tiny_mce.js',
                theme: "advanced",
                plugins: "paste",
                encoding: "utf-8",
                entities: "",
                convert_urls: false,
                theme_advanced_buttons3_add: "pastetext,pasteword,selectall",
                paste_auto_cleanup_on_paste: true
            });
        });


    </script>
{/literal}