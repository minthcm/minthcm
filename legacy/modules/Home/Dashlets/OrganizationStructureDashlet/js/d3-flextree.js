(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
	typeof define === 'function' && define.amd ? define(['exports'], factory) :
	(factory((global.d3 = global.d3 || {})));
}(this, (function (exports) { 'use strict';

function count(node) {
  var sum = 0,
      children = node.children,
      i = children && children.length;
  if (!i) sum = 1;
  else while (--i >= 0) sum += children[i].value;
  node.value = sum;
}

function node_count() {
  return this.eachAfter(count);
}

function node_each(callback) {
  var node = this, current, next = [node], children, i, n;
  do {
    current = next.reverse(), next = [];
    while (node = current.pop()) {
      callback(node), children = node.children;
      if (children) for (i = 0, n = children.length; i < n; ++i) {
        next.push(children[i]);
      }
    }
  } while (next.length);
  return this;
}

function node_eachBefore(callback) {
  var node = this, nodes = [node], children, i;
  while (node = nodes.pop()) {
    callback(node), children = node.children;
    if (children) for (i = children.length - 1; i >= 0; --i) {
      nodes.push(children[i]);
    }
  }
  return this;
}

function node_eachAfter(callback) {
  var node = this, nodes = [node], next = [], children, i, n;
  while (node = nodes.pop()) {
    next.push(node), children = node.children;
    if (children) for (i = 0, n = children.length; i < n; ++i) {
      nodes.push(children[i]);
    }
  }
  while (node = next.pop()) {
    callback(node);
  }
  return this;
}

function node_sum(value) {
  return this.eachAfter(function(node) {
    var sum = +value(node.data) || 0,
        children = node.children,
        i = children && children.length;
    while (--i >= 0) sum += children[i].value;
    node.value = sum;
  });
}

function node_sort(compare) {
  return this.eachBefore(function(node) {
    if (node.children) {
      node.children.sort(compare);
    }
  });
}

function node_path(end) {
  var start = this,
      ancestor = leastCommonAncestor(start, end),
      nodes = [start];
  while (start !== ancestor) {
    start = start.parent;
    nodes.push(start);
  }
  var k = nodes.length;
  while (end !== ancestor) {
    nodes.splice(k, 0, end);
    end = end.parent;
  }
  return nodes;
}

function leastCommonAncestor(a, b) {
  if (a === b) return a;
  var aNodes = a.ancestors(),
      bNodes = b.ancestors(),
      c = null;
  a = aNodes.pop();
  b = bNodes.pop();
  while (a === b) {
    c = a;
    a = aNodes.pop();
    b = bNodes.pop();
  }
  return c;
}

function node_ancestors() {
  var node = this, nodes = [node];
  while (node = node.parent) {
    nodes.push(node);
  }
  return nodes;
}

function node_descendants() {
  var nodes = [];
  this.each(function(node) {
    nodes.push(node);
  });
  return nodes;
}

function node_leaves() {
  var leaves = [];
  this.eachBefore(function(node) {
    if (!node.children) {
      leaves.push(node);
    }
  });
  return leaves;
}

function node_links() {
  var root = this, links = [];
  root.each(function(node) {
    if (node !== root) { // Don’t include the root’s parent, if any.
      links.push({source: node.parent, target: node});
    }
  });
  return links;
}

function hierarchy(data, children) {
  var root = new Node(data),
      valued = +data.value && (root.value = data.value),
      node,
      nodes = [root],
      child,
      childs,
      i,
      n;

  if (children == null) children = defaultChildren;

  while (node = nodes.pop()) {
    if (valued) node.value = +node.data.value;
    if ((childs = children(node.data)) && (n = childs.length)) {
      node.children = new Array(n);
      for (i = n - 1; i >= 0; --i) {
        nodes.push(child = node.children[i] = new Node(childs[i]));
        child.parent = node;
        child.depth = node.depth + 1;
      }
    }
  }

  return root.eachBefore(computeHeight);
}

function node_copy() {
  return hierarchy(this).eachBefore(copyData);
}

function defaultChildren(d) {
  return d.children;
}

function copyData(node) {
  node.data = node.data.data;
}

function computeHeight(node) {
  var height = 0;
  do node.height = height;
  while ((node = node.parent) && (node.height < ++height));
}

function Node(data) {
  this.data = data;
  this.depth =
  this.height = 0;
  this.parent = null;
}

Node.prototype = hierarchy.prototype = {
  constructor: Node,
  count: node_count,
  each: node_each,
  eachAfter: node_eachAfter,
  eachBefore: node_eachBefore,
  sum: node_sum,
  sort: node_sort,
  path: node_path,
  ancestors: node_ancestors,
  descendants: node_descendants,
  leaves: node_leaves,
  links: node_links,
  copy: node_copy
};

var name = "d3-flextree";
var version = "2.1.2";
var main = "build/d3-flextree.js";
var module$1 = "index";
var author = { "name": "Chris Maloney", "url": "http://chrismaloney.org" };
var description = "Flexible tree layout algorithm that allows for variable node sizes.";
var keywords = ["d3", "d3-module", "layout", "tree", "hierarchy", "d3-hierarchy", "plugin", "d3-plugin", "infovis", "visualization", "2d"];
var homepage = "https://github.com/klortho/d3-flextree";
var license = "WTFPL";
var repository = { "type": "git", "url": "https://github.com/klortho/d3-flextree.git" };
var scripts = { "clean": "rm -rf build demo test", "build:demo": "rollup -c --environment BUILD:demo", "build:dev": "rollup -c --environment BUILD:dev", "build:prod": "rollup -c --environment BUILD:prod", "build:test": "rollup -c --environment BUILD:test", "build": "rollup -c", "lint": "eslint index.js src", "test:main": "node test/bundle.js", "test:browser": "node test/browser-tests.js", "test": "npm-run-all test:*", "prepare": "npm-run-all clean build lint test" };
var dependencies = { "d3-hierarchy": "^1.1.5" };
var devDependencies = { "babel-plugin-external-helpers": "^6.22.0", "babel-preset-es2015-rollup": "^3.0.0", "d3": "^4.13.0", "d3-selection-multi": "^1.0.1", "eslint": "^4.19.1", "jsdom": "^11.6.2", "npm-run-all": "^4.1.2", "rollup": "^0.55.3", "rollup-plugin-babel": "^2.7.1", "rollup-plugin-commonjs": "^8.0.2", "rollup-plugin-copy": "^0.2.3", "rollup-plugin-json": "^2.3.0", "rollup-plugin-node-resolve": "^3.0.2", "rollup-plugin-uglify": "^3.0.0", "uglify-es": "^3.3.9" };
var packageInfo = {
  name: name,
  version: version,
  main: main,
  module: module$1,
  author: author,
  description: description,
  keywords: keywords,
  homepage: homepage,
  license: license,
  repository: repository,
  scripts: scripts,
  dependencies: dependencies,
  devDependencies: devDependencies,
  "jsnext:main": "index"
};

var classCallCheck = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

var createClass = function () {
  function defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  return function (Constructor, protoProps, staticProps) {
    if (protoProps) defineProperties(Constructor.prototype, protoProps);
    if (staticProps) defineProperties(Constructor, staticProps);
    return Constructor;
  };
}();









var inherits = function (subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      enumerable: false,
      writable: true,
      configurable: true
    }
  });
  if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
};











var possibleConstructorReturn = function (self, call) {
  if (!self) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return call && (typeof call === "object" || typeof call === "function") ? call : self;
};





var slicedToArray = function () {
  function sliceIterator(arr, i) {
    var _arr = [];
    var _n = true;
    var _d = false;
    var _e = undefined;

    try {
      for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
        _arr.push(_s.value);

        if (i && _arr.length === i) break;
      }
    } catch (err) {
      _d = true;
      _e = err;
    } finally {
      try {
        if (!_n && _i["return"]) _i["return"]();
      } finally {
        if (_d) throw _e;
      }
    }

    return _arr;
  }

  return function (arr, i) {
    if (Array.isArray(arr)) {
      return arr;
    } else if (Symbol.iterator in Object(arr)) {
      return sliceIterator(arr, i);
    } else {
      throw new TypeError("Invalid attempt to destructure non-iterable instance");
    }
  };
}();

var version$1 = packageInfo.version;

var defaults$1 = Object.freeze({
  children: function children(data) {
    return data.children;
  },
  nodeSize: function nodeSize(node) {
    return node.data.size;
  },
  spacing: 0
});

// Create a layout function with customizable options. Per D3-style, the
// options can be set at any time using setter methods. The layout function
// will compute the tree node positions based on the options in effect at the
// time it is called.
function flextree(options) {
  var opts = Object.assign({}, defaults$1, options);
  function accessor(name$$1) {
    var opt = opts[name$$1];
    return typeof opt === 'function' ? opt : function () {
      return opt;
    };
  }

  function layout(tree) {
    var wtree = wrap(getWrapper(), tree, function (node) {
      return node.children;
    });
    wtree.update();
    return wtree.data;
  }

  function getFlexNode() {
    var nodeSize = accessor('nodeSize');
    var _spacing = accessor('spacing');
    return function (_hierarchy$prototype$) {
      inherits(FlexNode, _hierarchy$prototype$);

      function FlexNode(data) {
        classCallCheck(this, FlexNode);
        return possibleConstructorReturn(this, (FlexNode.__proto__ || Object.getPrototypeOf(FlexNode)).call(this, data));
      }

      createClass(FlexNode, [{
        key: 'copy',
        value: function copy() {
          var c = wrap(this.constructor, this, function (node) {
            return node.children;
          });
          c.each(function (node) {
            return node.data = node.data.data;
          });
          return c;
        }
      }, {
        key: 'spacing',
        value: function spacing(oNode) {
          return _spacing(this, oNode);
        }
      }, {
        key: 'size',
        get: function get$$1() {
          return nodeSize(this);
        }
      }, {
        key: 'nodes',
        get: function get$$1() {
          return this.descendants();
        }
      }, {
        key: 'xSize',
        get: function get$$1() {
          return this.size[0];
        }
      }, {
        key: 'ySize',
        get: function get$$1() {
          return this.size[1];
        }
      }, {
        key: 'top',
        get: function get$$1() {
          return this.y;
        }
      }, {
        key: 'bottom',
        get: function get$$1() {
          return this.y + this.ySize;
        }
      }, {
        key: 'left',
        get: function get$$1() {
          return this.x - this.xSize / 2;
        }
      }, {
        key: 'right',
        get: function get$$1() {
          return this.x + this.xSize / 2;
        }
      }, {
        key: 'root',
        get: function get$$1() {
          var ancs = this.ancestors();
          return ancs[ancs.length - 1];
        }
      }, {
        key: 'numChildren',
        get: function get$$1() {
          return this.hasChildren ? this.children.length : 0;
        }
      }, {
        key: 'hasChildren',
        get: function get$$1() {
          return !this.noChildren;
        }
      }, {
        key: 'noChildren',
        get: function get$$1() {
          return this.children === null;
        }
      }, {
        key: 'firstChild',
        get: function get$$1() {
          return this.hasChildren ? this.children[0] : null;
        }
      }, {
        key: 'lastChild',
        get: function get$$1() {
          return this.hasChildren ? this.children[this.numChildren - 1] : null;
        }
      }, {
        key: 'extents',
        get: function get$$1() {
          return (this.children || []).reduce(function (acc, kid) {
            return FlexNode.maxExtents(acc, kid.extents);
          }, this.nodeExtents);
        }
      }, {
        key: 'nodeExtents',
        get: function get$$1() {
          return {
            top: this.top,
            bottom: this.bottom,
            left: this.left,
            right: this.right
          };
        }
      }], [{
        key: 'maxExtents',
        value: function maxExtents(e0, e1) {
          return {
            top: Math.min(e0.top, e1.top),
            bottom: Math.max(e0.bottom, e1.bottom),
            left: Math.min(e0.left, e1.left),
            right: Math.max(e0.right, e1.right)
          };
        }
      }]);
      return FlexNode;
    }(hierarchy.prototype.constructor);
  }

  function getWrapper() {
    var FlexNode = getFlexNode();
    var nodeSize = accessor('nodeSize');
    var _spacing2 = accessor('spacing');
    return function (_FlexNode) {
      inherits(_class, _FlexNode);

      function _class(data) {
        classCallCheck(this, _class);

        var _this2 = possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).call(this, data));

        Object.assign(_this2, {
          x: 0, y: 0,
          relX: 0, prelim: 0, shift: 0, change: 0,
          lExt: _this2, lExtRelX: 0, lThr: null,
          rExt: _this2, rExtRelX: 0, rThr: null
        });
        return _this2;
      }

      createClass(_class, [{
        key: 'spacing',
        value: function spacing(oNode) {
          return _spacing2(this.data, oNode.data);
        }
      }, {
        key: 'update',
        value: function update() {
          layoutChildren(this);
          resolveX(this);
          return this;
        }
      }, {
        key: 'size',
        get: function get$$1() {
          return nodeSize(this.data);
        }
      }, {
        key: 'x',
        get: function get$$1() {
          return this.data.x;
        },
        set: function set$$1(v) {
          this.data.x = v;
        }
      }, {
        key: 'y',
        get: function get$$1() {
          return this.data.y;
        },
        set: function set$$1(v) {
          this.data.y = v;
        }
      }]);
      return _class;
    }(FlexNode);
  }

  function wrap(FlexClass, treeData, children) {
    var _wrap = function _wrap(data, parent) {
      var node = new FlexClass(data);
      Object.assign(node, {
        parent: parent,
        depth: parent === null ? 0 : parent.depth + 1,
        height: 0,
        length: 1
      });
      var kidsData = children(data) || [];
      node.children = kidsData.length === 0 ? null : kidsData.map(function (kd) {
        return _wrap(kd, node);
      });
      if (node.children) {
        Object.assign(node, node.children.reduce(function (hl, kid) {
          return {
            height: Math.max(hl.height, kid.height + 1),
            length: hl.length + kid.length
          };
        }, node));
      }
      return node;
    };
    return _wrap(treeData, null);
  }

  Object.assign(layout, {
    nodeSize: function nodeSize(arg) {
      return arguments.length ? (opts.nodeSize = arg, layout) : opts.nodeSize;
    },
    spacing: function spacing(arg) {
      return arguments.length ? (opts.spacing = arg, layout) : opts.spacing;
    },
    children: function children(arg) {
      return arguments.length ? (opts.children = arg, layout) : opts.children;
    },
    hierarchy: function hierarchy(treeData, children) {
      var kids = typeof children === 'undefined' ? opts.children : children;
      return wrap(getFlexNode(), treeData, kids);
    },
    dump: function dump(tree) {
      var nodeSize = accessor('nodeSize');
      var _dump = function _dump(i0) {
        return function (node) {
          var i1 = i0 + '  ';
          var i2 = i0 + '    ';
          var x = node.x,
              y = node.y;

          var size = nodeSize(node);
          var kids = node.children || [];
          var kdumps = kids.length === 0 ? ' ' : ',' + i1 + 'children: [' + i2 + kids.map(_dump(i2)).join(i2) + i1 + '],' + i0;
          return '{ size: [' + size.join(', ') + '],' + i1 + 'x: ' + x + ', y: ' + y + kdumps + '},';
        };
      };
      return _dump('\n')(tree);
    }
  });
  return layout;
}
flextree.version = version$1;

var layoutChildren = function layoutChildren(w) {
  var y = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;

  w.y = y;
  (w.children || []).reduce(function (acc, kid) {
    var _acc = slicedToArray(acc, 2),
        i = _acc[0],
        lastLows = _acc[1];

    layoutChildren(kid, w.y + w.ySize);
    // The lowest vertical coordinate while extreme nodes still point
    // in current subtree.
    var lowY = (i === 0 ? kid.lExt : kid.rExt).bottom;
    if (i !== 0) separate(w, i, lastLows);
    var lows = updateLows(lowY, i, lastLows);
    return [i + 1, lows];
  }, [0, null]);
  shiftChange(w);
  positionRoot(w);
  return w;
};

// Resolves the relative coordinate properties - relX and prelim --
// to set the final, absolute x coordinate for each node. This also sets
// `prelim` to 0, so that `relX` for each node is its x-coordinate relative
// to its parent.
var resolveX = function resolveX(w, prevSum, parentX) {
  // A call to resolveX without arguments is assumed to be for the root of
  // the tree. This will set the root's x-coord to zero.
  if (typeof prevSum === 'undefined') {
    prevSum = -w.relX - w.prelim;
    parentX = 0;
  }
  var sum = prevSum + w.relX;
  w.relX = sum + w.prelim - parentX;
  w.prelim = 0;
  w.x = parentX + w.relX;
  (w.children || []).forEach(function (k) {
    return resolveX(k, sum, w.x);
  });
  return w;
};

// Process shift and change for all children, to add intermediate spacing to
// each child's modifier.
var shiftChange = function shiftChange(w) {
  (w.children || []).reduce(function (acc, child) {
    var _acc2 = slicedToArray(acc, 2),
        lastShiftSum = _acc2[0],
        lastChangeSum = _acc2[1];

    var shiftSum = lastShiftSum + child.shift;
    var changeSum = lastChangeSum + shiftSum + child.change;
    child.relX += changeSum;
    return [shiftSum, changeSum];
  }, [0, 0]);
};

// Separates the latest child from its previous sibling
/* eslint-disable complexity */
var separate = function separate(w, i, lows) {
  var lSib = w.children[i - 1];
  var curSubtree = w.children[i];
  var rContour = lSib;
  var rSumMods = lSib.relX;
  var lContour = curSubtree;
  var lSumMods = curSubtree.relX;
  var isFirst = true;
  while (rContour && lContour) {
    if (rContour.bottom > lows.lowY) lows = lows.next;
    // How far to the left of the right side of rContour is the left side
    // of lContour? First compute the center-to-center distance, then add
    // the "spacing"
    var dist = rSumMods + rContour.prelim - (lSumMods + lContour.prelim) + rContour.xSize / 2 + lContour.xSize / 2 + rContour.spacing(lContour);
    if (dist > 0 || dist < 0 && isFirst) {
      lSumMods += dist;
      // Move subtree by changing relX.
      moveSubtree$1(curSubtree, dist);
      distributeExtra(w, i, lows.index, dist);
    }
    isFirst = false;
    // Advance highest node(s) and sum(s) of modifiers
    var rightBottom = rContour.bottom;
    var leftBottom = lContour.bottom;
    if (rightBottom <= leftBottom) {
      rContour = nextRContour(rContour);
      if (rContour) rSumMods += rContour.relX;
    }
    if (rightBottom >= leftBottom) {
      lContour = nextLContour(lContour);
      if (lContour) lSumMods += lContour.relX;
    }
  }
  // Set threads and update extreme nodes. In the first case, the
  // current subtree is taller than the left siblings.
  if (!rContour && lContour) setLThr(w, i, lContour, lSumMods);
  // In the next case, the left siblings are taller than the current subtree
  else if (rContour && !lContour) setRThr(w, i, rContour, rSumMods);
};
/* eslint-enable complexity */

// Move subtree by changing relX.
var moveSubtree$1 = function moveSubtree(subtree, distance) {
  subtree.relX += distance;
  subtree.lExtRelX += distance;
  subtree.rExtRelX += distance;
};

var distributeExtra = function distributeExtra(w, curSubtreeI, leftSibI, dist) {
  var curSubtree = w.children[curSubtreeI];
  var n = curSubtreeI - leftSibI;
  // Are there intermediate children?
  if (n > 1) {
    var delta = dist / n;
    w.children[leftSibI + 1].shift += delta;
    curSubtree.shift -= delta;
    curSubtree.change -= dist - delta;
  }
};

var nextLContour = function nextLContour(w) {
  return w.hasChildren ? w.firstChild : w.lThr;
};

var nextRContour = function nextRContour(w) {
  return w.hasChildren ? w.lastChild : w.rThr;
};

var setLThr = function setLThr(w, i, lContour, lSumMods) {
  var firstChild = w.firstChild;
  var lExt = firstChild.lExt;
  var curSubtree = w.children[i];
  lExt.lThr = lContour;
  // Change relX so that the sum of modifier after following thread is correct.
  var diff = lSumMods - lContour.relX - firstChild.lExtRelX;
  lExt.relX += diff;
  // Change preliminary x coordinate so that the node does not move.
  lExt.prelim -= diff;
  // Update extreme node and its sum of modifiers.
  firstChild.lExt = curSubtree.lExt;
  firstChild.lExtRelX = curSubtree.lExtRelX;
};

// Mirror image of setLThr.
var setRThr = function setRThr(w, i, rContour, rSumMods) {
  var curSubtree = w.children[i];
  var rExt = curSubtree.rExt;
  var lSib = w.children[i - 1];
  rExt.rThr = rContour;
  var diff = rSumMods - rContour.relX - curSubtree.rExtRelX;
  rExt.relX += diff;
  rExt.prelim -= diff;
  curSubtree.rExt = lSib.rExt;
  curSubtree.rExtRelX = lSib.rExtRelX;
};

// Position root between children, taking into account their modifiers
var positionRoot = function positionRoot(w) {
  if (w.hasChildren) {
    var k0 = w.firstChild;
    var kf = w.lastChild;
    var prelim = (k0.prelim + k0.relX - k0.xSize / 2 + kf.relX + kf.prelim + kf.xSize / 2) / 2;
    Object.assign(w, {
      prelim: prelim,
      lExt: k0.lExt, lExtRelX: k0.lExtRelX,
      rExt: kf.rExt, rExtRelX: kf.rExtRelX
    });
  }
};

// Make/maintain a linked list of the indexes of left siblings and their
// lowest vertical coordinate.
var updateLows = function updateLows(lowY, index, lastLows) {
  // Remove siblings that are hidden by the new subtree.
  while (lastLows !== null && lowY >= lastLows.lowY) {
    lastLows = lastLows.next;
  } // Prepend the new subtree.
  return {
    lowY: lowY,
    index: index,
    next: lastLows
  };
};

exports.flextree = flextree;

Object.defineProperty(exports, '__esModule', { value: true });

})));
//# sourceMappingURL=d3-flextree.js.map
