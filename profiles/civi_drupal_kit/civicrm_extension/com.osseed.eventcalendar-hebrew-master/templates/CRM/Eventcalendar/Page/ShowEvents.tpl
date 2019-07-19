{if $eventTypes == TRUE}
  <select id="event_selector" class="crm-form-select crm-select2 crm-action-menu fa-plus">
    <option value="all">{ts}All{/ts}</option>
    {foreach from=$eventTypes item=type}
    <option value="{$type}">{$type}</option>
    {/foreach}
  </select>
{/if}
<div id="calendar"></div>
{literal}
<script type="text/javascript">
 if (typeof(jQuery) != 'function')
     var jQuery = cj;
 cj( function( ) {
    buildCalendar( );
  });
 function buildCalendar( ) {
   var events_data = {/literal}{$civicrm_events}{literal};
   var jsonStr = JSON.stringify(events_data);
   var showTime = events_data.timeDisplay;
   var showSunset = events_data.sunsetDisplay;
   var showCandletime = events_data.candleDisplay;

   jQuery('#calendar').fullCalendar({
     eventSources:  events_data.events,
     header: events_data.header,
     displayEventTime: showTime ? 1 : 0,
     timeFormat: 'h(:mm)A',
     eventDataTransform: function(json) {
       let eventTitle = json.title;
       // Format the title to explore comma and word of form the event title
       // only for event of hebrew cal
       if (json.hebrew) {
         var eventTitleObj = {
           'of':" ",
           ',':" ",
         };
         json.title = eventTitle.replace(/of|,/gi, function(matched){
           return eventTitleObj[matched];
         });

         // Display sunset time
         if(showSunset == 1 && showCandletime == 0) {
            if (eventTitle.replace("Candle lighting", "") === "") {
                json.title = "Sunset: " + moment(json.start).add(18, "m").format("h:mmA");
            }
          }
          if(showSunset == 1 && showCandletime == 1) {
            if (eventTitle.replace("Candle lighting", "") === "") {
                json.title = "Candle lighting: " + moment(json.start).format("h:mmA");
                json.allDay = true;
            }
          }
          if(showSunset == 0 && showCandletime == 1) {
            if (eventTitle.replace("Candle lighting", "") === "") {
                json.title = "Candle lighting: " + moment(json.start).format("h:mmA");
                json.allDay = true;
            }
          }

          // Display sunset time & candle lightning time
          if(showSunset == 1 && showCandletime == 1) {
             if (eventTitle.replace("Candle lighting", "") === "") {
               json.title = json.title + '\n' +  "Sunset: " + moment(json.start).add(18, "m").format("h:mmA");
             }
          }
        }
       return json;
     },

     // Display sunset time and remove candle lighting time
     eventRender: function eventRender( event, element, view ) {
       if(showSunset==1 && showCandletime == 0) {
         cj(element).find(".fc-time").remove();
       }
       if(event.eventType && events_data.isfilter == "1" ) {
         return ['all', event.eventType].indexOf(cj('#event_selector').val()) >= 0
       }
     }
   });

   cj('#event_selector').on('change', function(){
      jQuery('#calendar').fullCalendar('rerenderEvents');
   })
 }
</script>
{/literal}
