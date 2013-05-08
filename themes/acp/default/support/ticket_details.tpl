<style type="text/css" media="all">@import "{$turl}/css/tickets.css";</style>
<!--<link rel="stylesheet" href="{$vurl}/js/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
<script type="text/javascript" src="{$vurl}/js/jwysiwyg/jquery.wysiwyg.js"></script>-->

<script type="text/javascript" src="{$vurl}/js/jquery.treeview.pack.js"></script>
<link rel="stylesheet" href="{$turl}/css/jquery.treeview.css" type="text/css"/>

{if $mobile == false}
    <script type="text/javascript" src="{$vurl}/js/tiny_mce/jquery.tinymce.js"></script>
{/if}


<div class="ticket_wrapper">
    <div class="ticket_ticket">
        <ul class="ticket_content">
            <li>
                <textarea rows="8" name="message" id="message" class="tinymce" style="width: 660px;  "></textarea>
            </li>
            <li class="response">
                <div class="ticket_post">
                    <div id="buttons" style="position: relative;">
                        <input type="submit" value="Cevabı Ekle" id="buttonSubmit" style="float:right;"/>
                    <span style="float: right; margin:5px;"><label>
                            <input type="checkbox" id="setas_awaiting_reply" value="1" checked="checked"> Cevap
                            Bekleniyor</label>
                    </span>
                    <span style="float: right; margin:5px;"><label>
                            <input type="checkbox" id="private" value="1"> Gizli Mesaj</label>
                    </span>
                    </div>

                    <table>
                        <tr>
                            <td><input type="button" id="upload_button" value="Dosya Yükle"></td>
                            <td><input type="button" onclick="$('#quick_replies').slideToggle(); "
                                       value="Hazır Cevaplar"></td>
                            <td><a href="{$vurl}/acp/ajax/ajax.kb.php?a=get_kb_list&height=400&width=800" class="button"
                                   rel="facebox">Bilgi Bankası</a></td>
                        </tr>
                    </table>
                    <div id="quick_replies" style="padding:2px 0 10px 0; display: none;">
                        <select id="qreps" style="width: 300px;">
                            <option value="">Hazır Cevaplar</option>
                            {foreach from=$qreps item=r}
                                <option value="{$r.qrepID}">{$r.reply}</option>
                            {/foreach}
                        </select>
                        <input type="image" src="{$turl}images/add.png" id="qrepbut">
                    </div>
                </div>
            </li>
            <li class="files">
                <ul id="files">
                    {foreach from=$attachments item=a}
                        <li><a href="file.php?id={$a.fileID}">{$a.origname}</a></li>
                    {/foreach}
                </ul>
            </li>
        </ul>


        <div class="clear"></div>
        <ul class="ticket_content" id="ticket_content"></ul>
    </div>
    <div class="ticket_console">
        <div class="user_avatar">
            <img src='image.php?f={$T->Client->getAvatarName()}&t=avatar&w=70&h=50'>

            <div id="user_info">
                <a href="index.php?p=311&clientID={$T->clientID}">{if $T->Client->type == 'individual'}{$T->Client->name}{else}{$T->Client->company}{/if}</a>
            </div>
        </div>
        <ul>
            <li class="header">Açılış</li>
            <li>{format_date date=$T->dateAdded mode=datetime}</li>
            <li class="header">Son Güncelleme</li>
            <li>{format_date date=$T->dateUpdated mode=datetime}</li>
            <li class="header">Durum</li>
            <li><span></span>
                <select name="status" class="select" id="status">
                    {foreach from=$vars.TICKET_STATUS_TYPES item=st}
                        <option value="{$st}" {if $st  == $T->status }selected{/if}>##TicketDetails%{$st}##</option>
                    {/foreach}
                </select>
                <input type="hidden" id="status_o" value="{$T->status}">
            </li>
            <li class="header">Departman</li>
            <li><span></span>
                <select name="depID" class="select" id="depID">
                    {foreach from=$deps item=d}
                        <option value="{$d.depID}" {if $d.depID  == $T->depID }selected{/if}>{$d.depTitle}</option>
                    {/foreach}
                </select>
                <input type="hidden" id="depID_o" value="{$T->depID}">
            </li>
            <li class="header">İlgili</li>
            <li><span></span>
                <select name="adminID" class="select" id="adminID">
                    <option value='-1' {if $T->adminID == -1}selected{/if}>Atanmamış</option>
                    {foreach from=$admins item=a}
                        <option value="{$a.adminID}"
                                {if $a.adminID  == $T->adminID }selected{/if}>{$a.adminNick}</option>
                    {/foreach}
                </select>
                <input type="hidden" id="adminID_o" value="{$T->adminID}">
            </li>
            <li class="header">##TicketDetails%Priority##</li>
            <li><span></span>
                <select name="priority" class="select" id="priority">
                    {foreach from=$vars.PRIORITY_OPTIONS item=p key=k}
                    <option value="{$k}" {if $k == $T->priority}selected{/if}>##TicketDetails%{$p}##</option>
                    {/foreach}}
                </select>
                <input type="hidden" id="priority_o" value="{$T->priority}">
            </li>
        </ul>
    </div>
</div>


<script language="JavaScript">
    var timestamp = 0;
    var mobile = {if $mobile}true{else}false{/if};
    var timer_{'-'|str_replace:'':$T->ticketID};
    var ticketID = '{$T->ticketID}';
    var turl = '{$turl}';
    var avatar = '';
    var vurl = '';
    var client_name = '{$T->Client->name}';
</script>
<script src="{$vurl}js/acp.ticket_details.js" type="text/javascript"></script>
<script language="JavaScript">
    getMessages();
    timer_{'-'|str_replace:'':$T->ticketID} = setInterval("getMessages()", 5000);
</script>


<script src="{$vurl}js/jquery.ajaxupload.js" type="text/javascript"></script>
{literal}
    <script language="JavaScript">
        new AjaxUpload('upload_button', {
            // Location of the server-side upload script
            // NOTE: You are not allowed to upload files to another domain
            action: '?p=212&ticketID=' + ticketID,
            // File upload name
            name: 'file',
            // Additional data to send
            data: {
                action: 'upload_file'
            },
            // Submit file after selection
            autoSubmit: true,
            // The type of data that you're expecting back from the server.
            // HTML (text) and XML are detected automatically.
            // Useful when you are using JSON data as a response, set to "json" in that case.
            // Also set server response type to text/html, otherwise it will not work in IE6
            responseType: "json",
            // Fired after the file is selected
            // Useful when autoSubmit is disabled
            // You can return false to cancel upload
            // @param file basename of uploaded file
            // @param extension of that file
            onChange: function (file, extension) {
            },
            // Fired before the file is uploaded
            // You can return false to cancel upload
            // @param file basename of uploaded file
            // @param extension of that file
            onSubmit: function (file, extension) {
                var li = "<li class='delme'><img src='" + turl + "/images/loading.gif'></li>"
                $("#files").append(li);
            },
            // Fired when file upload is completed
            // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
            // @param file basename of uploaded file
            // @param response server response
            onComplete: function (file, r) {
                chatTimer = 0;
                getMessages();
                if (r.st) {
                    var li = "<li  class='clearfix'><a href='file.php?id=" + r.fileID + "'>" + r.filename + '</a><li>';
                    $("#files").append(li);
                    $(".delme").remove();
                } else {
                    alert(r.msg);
                }
            }
        });

        $(document).ready(function () {
            $('a[rel*=facebox]').facebox();

            if (mobile == false) {
                $('textarea.tinymce').tinymce({
                    script_url: v_url + '/js/tiny_mce/tiny_mce.js',
                    theme: "advanced",
                    plugins: "paste",
                    encoding: "utf-8",
                    entities: "",
                    convert_urls: false,
                    theme_advanced_buttons3_add: "pastetext,pasteword,selectall",
                    paste_auto_cleanup_on_paste: true
                });
            }
        });
    </script>
{/literal}


