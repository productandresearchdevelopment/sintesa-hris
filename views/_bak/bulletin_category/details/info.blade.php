<script>
  var InfoDetail = function() {
    let me = this;
    me.init = function() {
      me.panel = Ext.create('Ext.panel.Panel', {
        id: 'detail-tab-info',
        title: 'INFO',
        border: true,
        autoScroll: true,
        flex: 1,
        html: `<div id="view-detail" style="position: absolute; top: 0; left:0; right:0; background: #FAFAFA">
                                <div style="font-size: 11px; padding: 30px 0px; color: #ccc; text-align: center">
                                    NO DISPLAY DATA
                                </div>
                           </div>`
      });
    }

    me.hexToRgba = function(hex, opacity) {
      let r = parseInt(hex.substring(1, 3), 16);
      let g = parseInt(hex.substring(3, 5), 16);
      let b = parseInt(hex.substring(5, 7), 16);
      return `rgba(${r}, ${g}, ${b}, ${opacity})`;
    }

    me.set = function(data) {
      if (data) {
        const fileRoute = "{{ route('file', ':id') }}";
        data = {
          ...data,
          background_color: data.color ? me.hexToRgba(data.color, 0.3) : 'rgba(0, 123, 255, 0.3)',
          bulletins: data.bulletins ?
            data.bulletins.map(b => ({
              ...b,
              image_url: b.cover_image_id ? fileRoute.replace(':id', b.cover_image_id) : 'default-image.jpg'
            })) : []
        };

        me.panel.update(String.format(`@require('tpl.info')`, data));
      }
    }


    me.init();
  }
</script>
