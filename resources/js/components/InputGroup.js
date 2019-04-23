import React from 'react';
import {Form} from 'react-bootstrap';
import PropTypes from 'prop-types';

class InputGroup extends React.Component{
    constructor(props){
        super(props);
    }
    handlerChange = (e) => {
        this.props.onChange(e.target.value);
    }
    static propTypes = {
        value: "",
        label: "",
        placeholder: "",
        title: "",
        error: null
    }
    render() {
        return(
            <Form.Group>
                {this.props.label ? <Form.Label>{this.props.label}</Form.Label> : ""}
                <Form.Control
                    type={this.props.type}
                    name={this.props.name}
                    title={this.props.title}
                    placeholder={this.props.placeholder}
                    onChange={this.handlerChange}
                    className={this.props.error ? "is-invalid" : ""}
                />
                {this.props.error ? <div className="invalid-feedback">{this.props.error}</div> : ""}

            </Form.Group>
        );
    }
}

InputGroup.propTypes = {
    value: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number,
        PropTypes.bool
    ]),
    label: PropTypes.string,
    placeholder: PropTypes.string,
    title: PropTypes.string,
    name: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
    error: PropTypes.string,
    onChange: PropTypes.func.isRequired
};

export default InputGroup;