{* HEADER *}

{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}

{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}
      {if $descriptions.$elementName}<br /><span class="description">{$descriptions.$elementName}</span>{/if}
    </div>
    <div class="clear"></div>
  </div>
{/foreach}

{* FIELD EXAMPLE: OPTION 2 (MANUAL LAYOUT)

  <div>
    <span>{$form.favorite_color.label}</span>
    <span>{$form.favorite_color.html}</span>
  </div>

{* FOOTER *}
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
{literal}
<script type="text/javascript">
 if (typeof(jQuery) != 'function')
     var jQuery = cj;
     cj( function( ) {
       cj("input[name='eventcalendar_event_candlelighting_geotype_zip']").each(function () {
         if(cj(this).attr('checked')) {
           if(cj(this).val() == 0){
              cj('#eventcalendar_event_country_zipcode').attr("disabled", "disabled");
              cj('#eventcalendar_event_country_zipcode').val('');
            }
          }
        });
        cj("input[name='eventcalendar_event_candlelighting_geotype_lat']").each(function () {
          if(cj(this).attr('checked')) {
            if(cj(this).val() == 0){
               cj('#eventcalendar_event_candlelighting_longitude').attr("disabled", "disabled");
               cj('#eventcalendar_event_candlelighting_longitude').val('');
               cj('#eventcalendar_event_candlelighting_latitude').attr("disabled", "disabled");
               cj('#eventcalendar_event_candlelighting_latitude').val('');
             }
           }
         });
       cj("input[name='eventcalendar_event_candlelighting_geotype_zip']").each(function () {
         cj(this).change(function () {
           if( cj(this).val() == "0") {
             cj('#eventcalendar_event_country_zipcode').val('');
             cj('#eventcalendar_event_country_zipcode').attr("disabled", "disabled");
           } else {
             cj('#eventcalendar_event_country_zipcode').removeAttr("disabled");
           }
         });
       });
       cj("input[name='eventcalendar_event_candlelighting_geotype_lat']").each(function () {
         cj(this).change(function () {
           if(cj(this).val() == "0") {
             cj('#eventcalendar_event_candlelighting_latitude').val('');
             cj('#eventcalendar_event_candlelighting_longitude').val('');
             cj('#eventcalendar_event_candlelighting_longitude').attr("disabled", "disabled");
             cj('#eventcalendar_event_candlelighting_latitude').attr("disabled", "disabled");
           } else {
             cj('#eventcalendar_event_candlelighting_longitude').removeAttr("disabled");
             cj('#eventcalendar_event_candlelighting_latitude').removeAttr("disabled");
             }
         });
       });
       cj('input[name=eventcalendar_event_rosh_chodesh],input[name=eventcalendar_event_special_shabbatot]').click(function(){
         if(cj(this).is(":checked")){
           cj('input[name=eventcalendar_event_jewish_holidays]').eq(cj(this).index()).prop('checked' , true);
         }
       });
       cj('input[name=eventcalendar_event_jewish_holidays]').click(function(){
        if(cj(this).val() == 0){
          cj('input[name=eventcalendar_event_rosh_chodesh], input[name=eventcalendar_event_special_shabbatot').prop('checked', true);
        }
      });
     });
</script>
{/literal}
