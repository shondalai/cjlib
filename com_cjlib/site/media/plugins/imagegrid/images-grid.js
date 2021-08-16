
;(function($) {

    /**
     * Plugin
     */

    $.fn.imagesGrid = function(options) {

        var args = arguments;

        return this.each(function() {

            // If options is plain object - destroy previous instance and create new
            if ($.isPlainObject(options)) {
                
                if (this._imgGrid instanceof ImagesGrid) {
                    this._imgGrid.destroy();
                    delete this._imgGrid;
                }

                var opts = $.extend({}, $.fn.imagesGrid.defaults, options);
                opts.element = $(this);
                this._imgGrid = new ImagesGrid(opts);

                return;
            }

            // If options is string - execute method
            if (typeof options === 'string' && this._imgGrid instanceof ImagesGrid) {
                switch (options) {
                    case 'destroy':
                        this._imgGrid.destroy();
                        delete this._imgGrid;
                        break;
                }
            }

        });

    };

    /**
     * Plugin default options
     */

    $.fn.imagesGrid.defaults = {
        images: [],
        cells: 5,
        align: false,
        nextOnClick: true,
        showViewAll: 'more',
        viewAllStartIndex: 'auto',
        loading: 'loading...',
        getViewAllText: function(imagesCount) {
            return 'View all ' + imagesCount + ' images';
        },
        onGridRendered: $.noop,
        onGridItemRendered: $.noop,
        onGridLoaded: $.noop,
        onGridImageLoaded: $.noop
    };

    /**
     * ImagesGrid
     *   opts                    - Grid options 
     *   opts.element            - Element where to render images grid
     *   opts.images             - Array of images. Array item can be string or object { src, alt, title, caption, thumbnail }
     *   opts.align              - Align images with different height
     *   opts.cells              - Maximum number of cells (from 1 to 6)
     *   opts.showViewAll        - Show view all text:
     *                                'more'   - show if number of images greater than number of cells
     *                                'always' - always show
     *                                false    - never show
     *   opts.viewAllStartIndex  - Start image index when view all link clicked
     *   opts.getViewAllText     - Callback function returns text for "view all images" link
     *   opts.onGridRendered     - Callback function fired when grid items added to the DOM
     *   opts.onGridItemRendered - Callback function fired when grid item added to the DOM
     *   opts.onGridLoaded       - Callback function fired when grid images loaded
     *   opts.onGridImageLoaded  - Callback function fired when grid image loaded
     */

    function ImagesGrid(opts) {

        this.opts = opts || {};

        this.$window = $(window);
        this.$element = this.opts.element;
        this.$gridItems = [];

        this.modal = null;
        this.imageLoadCount = 0;

        var cells = this.opts.cells;
        this.opts.cells = (cells < 1)? 1: (cells > 6)? 6: cells;

        this.onWindowResize = this.onWindowResize.bind(this);
        this.init();
    }

    ImagesGrid.prototype.init = function()  {

        this.setGridClass();
        this.renderGridItems();

        this.$window.on('resize', this.onWindowResize);
    }

    ImagesGrid.prototype.setGridClass = function() {

        var opts = this.opts,
            imgsLen = opts.images.length,
            cellsCount = (imgsLen < opts.cells)? imgsLen: opts.cells;

        this.$element.addClass('imgs-grid imgs-grid-' + cellsCount);
    }

    ImagesGrid.prototype.renderGridItems = function() {

        var opts = this.opts,
            imgs = opts.images,
            imgsLen = imgs.length,
            render = true;

        if (!imgs) {
            return;
        }

        this.$element.empty();
        this.$gridItems = [];

        for (var i = 0; i < imgsLen; ++i) {
            if (i >= opts.cells) {
            	imgs[i].claz = 'photo hidden';
            	render = false;
            }
            this.renderGridItem(imgs[i], i, render);
        }

        if (opts.showViewAll === 'always' || 
            (opts.showViewAll === 'more' && imgsLen > opts.cells)
        ) {
            this.renderViewAll();
        }

        opts.onGridRendered(this.$element);
    }

    ImagesGrid.prototype.renderGridItem = function(image, index, render) {

        var src = image,
            alt = '',
            title = '',
            opts = this.opts,
            _this = this,
            style = '',
            href = '',
            html = '',
            claz = '';

        if ($.isPlainObject(image)) {
            src = image.thumbnail || image.src;
            alt = image.alt || '';
            title = image.title || '';
            style = image.style || '';
            href = image.href || '';
            html = image.html || '';
            claz = image.claz || '';
        }
        
//        var item = $('<div>', {
//            class: 'imgs-grid-image',
//            click: this.onImageClick,
//            data: { index: index }
//        });

        var item = $('<div>', {
            'class': 'imgs-grid-image ' + claz,
            'data': { 'index': index },
            'href': href,
            'data-sub-html': html
        });
        
        var imageToAppend = $('<div>', {class: 'image-wrap'});
        if(render) {
        	imageToAppend.append(
                    $('<img>', {
                        src: src,
                        alt: alt,
                        title: title,
                        on: {
                            load: function(event) {
                                _this.onImageLoaded(event, $(this), image);
                            }
                        }
                    })
                );
        }

        item.append(imageToAppend);

        this.$gridItems.push(item);
        this.$element.append(item);

        opts.onGridItemRendered(item, image);
    }

    ImagesGrid.prototype.renderViewAll = function() {

        var opts = this.opts;

        this.$element.find('.imgs-grid-image:not(.hidden):last .image-wrap').append(
            $('<div>', {
                class: 'view-all'
            }).append(
                $('<span>', {
                    class: 'view-all-cover',
                }),
                $('<span>', {
                    class: 'view-all-text',
                    text: opts.getViewAllText(opts.images.length)
                })
            )
        );
    }

    ImagesGrid.prototype.onWindowResize = function(event) {
        if (this.opts.align) {
            this.align();
        }
    }

    ImagesGrid.prototype.onImageLoaded = function(event, imageEl, image) {

        var opts = this.opts;

        ++this.imageLoadCount;

        opts.onGridImageLoaded(event, imageEl, image);

        if (this.imageLoadCount === this.$gridItems.length) {
            this.imageLoadCount = 0;
            this.onAllImagesLoaded()
        }
    }

    ImagesGrid.prototype.onAllImagesLoaded = function() {

        var opts = this.opts;

        if (opts.align) {
            this.align();
        }

        opts.onGridLoaded(this.$element);
    }

    ImagesGrid.prototype.align = function() {

        var itemsLen = this.$gridItems.length;

        switch (itemsLen) {
            case 2:
            case 3:
                this.alignItems(this.$gridItems);
                break;
            case 4:
                this.alignItems(this.$gridItems.slice(0, 2));
                this.alignItems(this.$gridItems.slice(2));
                break;
            case 5:
            case 6:
                this.alignItems(this.$gridItems.slice(0, 3));
                this.alignItems(this.$gridItems.slice(3));
                break;
        }
    }

    ImagesGrid.prototype.alignItems = function(items) {

        var itemsHeight = items.map(function(item) {
            return item.find('img').height();
        });

        var normalizedHeight = Math.min.apply(null, itemsHeight);

        $(items).each(function() {

            var item = $(this),
                imgWrap = item.find('.image-wrap'),
                img = item.find('img'),
                imgHeight = img.height();

            imgWrap.height(normalizedHeight);

            if (imgHeight > normalizedHeight) {
                var top = Math.floor((imgHeight - normalizedHeight) / 2);
                img.css({ top: -top });
            }
        });
    }

    ImagesGrid.prototype.destroy = function() {

        this.$window.off('resize',this.onWindowResize);

        this.$element.empty()
            .removeClass('imgs-grid imgs-grid-' + this.$gridItems.length);
    }
})(jQuery);
