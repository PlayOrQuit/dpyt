import React from 'react';
import PropTypes from 'prop-types';
import {Card, } from 'react-bootstrap';

class CardLoaderReact extends React.Component{
    constructor(props){
        super(props);
    }
    static propTypes = {
        isLoading: false,
        isFullscreen: false,
        title: null,
        position: null,
        color: null
    }
    render() {
        return(
            <Card>
                {this.props.color ? <div className={"card-status " + this.props.color + this.props.position ? this.props.position : ""}></div> : ""}
                {this.props.title ? <Card.Header><Card.Title>{this.props.title}</Card.Title>{this.props.isFullscreen ? <Card.Options><a href="javascript:;" className="card-options-fullscreen" data-toggle="card-fullscreen"><i className="fe fe-maximize"></i></a></Card.Options> : ""}</Card.Header> : ""}
                <Card.Body>
                    <div className={this.props.isLoading ? "dimmer active" : "dimmer"}>
                        <div className="loader"></div>
                        <div className="dimmer-content">
                            {this.props.children}
                        </div>
                    </div>
                </Card.Body>
            </Card>
        );
    }
}
CardLoaderReact.propTypes = {
    isLoading: PropTypes.bool,
    isFullscreen: PropTypes.bool,
    title: PropTypes.string,
    position: PropTypes.oneOf(['left', 'top']),
    color: PropTypes.oneOf(['blue', 'green', 'orange', 'red', 'yellow', 'teal', 'purple']),
    children: PropTypes.element
}
export default CardLoaderReact;