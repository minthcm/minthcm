if ( !window.TimerDashlet ) {
   TimerDashlet = function ( root ) {

      var _this = this;

      this.index = TimerDashlet.instances.push( this );
      this.storageKey = 'TimerDashlet_' + this.index;
      this.seconds = 0;
      this.started = 0;
      this.timerHandler = void 0;
      this.status = 'stopped';

      this.$root = root;
      this.$play = root.querySelector( '.button-play' );
      this.$pause = root.querySelector( '.button-pause' );
      this.$stop = root.querySelector( '.button-stop' );
      this.$timer = root.querySelector( '.timer-output' );
      this.$input = root.querySelector( '.timer-input' );


      $( _this.$pause ).hide();
      $( _this.$stop ).hide();
      $( _this.$input ).hide();
      $( _this.$timer ).hide();
      this.readStorage();

      this.$play.addEventListener( 'click', function () {
         if ( _this.status == 'stopped' ) {
            _this.stop();
            _this.started = +new Date;
         }
         _this.start();
         _this.saveStorage();
      } );
      this.$pause.addEventListener( 'click', function () {
         _this.pause();
         _this.saveStorage();
      } );
      this.$stop.addEventListener( 'click', function () {
         _this.stop();
         _this.clearStorage();
      } );
      this.$input.addEventListener( 'change', function () {
         _this.saveStorage();
      } );
   };


   TimerDashlet.instances = [ ];
   TimerDashlet.toHHMMSS = function ( time ) {
      var sec_num = parseInt( time, 10 ),
              hours = Math.floor( sec_num / 3600 ),
              minutes = Math.floor( (sec_num - (hours * 3600)) / 60 ),
              seconds = sec_num - (hours * 3600) - (minutes * 60);

      if ( hours < 10 ) {
         hours = "0" + hours;
      }
      if ( minutes < 10 ) {
         minutes = "0" + minutes;
      }
      if ( seconds < 10 ) {
         seconds = "0" + seconds;
      }

      return hours + ':' + minutes + ':' + seconds;
   };
   TimerDashlet.prototype.saveStorage = function () {
      var obj = {
         started: this.started,
         seconds: this.seconds,
         status: this.status,
         desc: this.$input.value
      };
      localStorage.setItem( this.storageKey, JSON.stringify( obj ) );
   };
   TimerDashlet.prototype.readStorage = function () {
      var stored = localStorage.getItem( this.storageKey );
      if ( stored ) {
         stored = JSON.parse( stored );
         this.started = stored.started;
         this.status = stored.status;
         this.$input.value = stored.desc || '';
         if ( this.status == 'running' ) {
            this.seconds = parseInt( (+new Date - this.started) / 1000 );
            this.start();
         } else {
            if ( this.status == 'paused' ) {
               $( this.$input ).show();
               $( this.$timer ).show();
            }
            this.seconds = stored.seconds;
            this.step();
         }
      }
      ;
   };
   TimerDashlet.prototype.clearStorage = function () {
      localStorage.removeItem( this.storageKey );
   };
   TimerDashlet.prototype.step = function () {
      var html = this.status == 'stopped' ? '' : TimerDashlet.toHHMMSS( this.seconds );
      this.$timer.innerHTML = html;
      this.seconds++;
   };

   TimerDashlet.prototype.start = function () {
      if ( this.status == 'stopped' )
         this.step();
      this.status = 'running';
      this.timerHandler = setInterval( this.step.bind( this ), 1000 );
      $( this.$play ).hide();
      $( this.$pause ).show();
      $( this.$stop ).hide();
      $( this.$input ).show();
      $( this.$timer ).show();
   };

   TimerDashlet.prototype.stop = function () {
      this.pause();
      this.seconds = 0;
      this.started = 0;
      this.status = 'stopped';
      $( this.$play ).show();
      $( this.$pause ).hide();
      $( this.$stop ).hide();
      $( this.$timer ).hide();
      $( this.$input ).val( '' ).hide();
   };

   TimerDashlet.prototype.pause = function () {
      this.timerHandler = clearInterval( this.timerHandler );
      this.status = 'paused';
      $( this.$play ).show();
      $( this.$pause ).hide();
      $( this.$stop ).show();
      $( this.$input ).show();
      $( this.$timer ).show();
   };

}