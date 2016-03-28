var ReactDOM = require('react-dom');

//app
var Header = require('./app/header');
var LeftSide = require('./app/leftside');
var RightSide = require('./app/rightside');
var Footer = require('./app/footer');
var Login = require('./app/login');

var App = React.createClass({
    render: function () {
        <div className="hold-transition skin-blue sidebar-mini">
            <Header />
            <LeftSide />
            <div className="content-wrapper">
                <section className="content" id="content">
                </section>
            </div>
            <Footer />
            <RightSide />
        </div>
    }
});
