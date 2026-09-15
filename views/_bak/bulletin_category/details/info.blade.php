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
      if (!hex) return `rgba(0, 123, 255, ${opacity})`;
      hex = hex.replace('#', '');
      let r = parseInt(hex.substring(0, 2), 16) || 0;
      let g = parseInt(hex.substring(2, 4), 16) || 0;
      let b = parseInt(hex.substring(4, 6), 16) || 0;
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
              image_url: b.cover_image_id ? fileRoute.replace(':id', b.cover_image_id) : '{{ asset('images/noimage.png') }}'
            })) : []
        };

        me.panel.update(String.format(`@require('tpl.info')`, data));
      }
    }


    me.init();
  }
</script>
