import React from 'react';
import {Alert} from 'react-bootstrap';
import PropTypes from "prop-types";

class AlertMessage extends React.Component{
    static propTypes = {
        show: false,
        type: 'info',
        dismissible: false,
        onDismiss: () => {}
    }
    render() {
        return(
            <Alert show={this.props.show} variant={this.props.type} dismissible={this.props.dismissible} onClose={this.props.onDismiss}>
                {this.props.messages.length > 1 ?
                    <ul>
                        {this.props.messages.map(msg => {
                            <li>{msg}</li>
                        })}
                    </ul>
                    : <p>{this.props.messages}</p>
                }
            </Alert>
        );
    }
}
AlertMessage.propTypes = {
    messages: PropTypes.array.isRequired,
    type: PropTypes.oneOf(['danger', 'info', 'success', 'warning']),
    dismissible: PropTypes.bool,
    show: PropTypes.bool,
    onDismiss: PropTypes.func
}
export default AlertMessage;
