
import React from 'react';
import PropTypes from 'prop-types';
import {
    OverlayTrigger,
    Tooltip
} from 'react-bootstrap';


class LabelToolTip extends React.Component {
    static propTypes = {
        title: null,
        tooltip: null,
        id: null,
        className: null
    }

    constructor(props) {
        super(props);
    }

    render() {
        const cN = this.props.className + " form-label";
        return (
            <>
                <label className={cN}>
                    {this.props.title}
                    { this.props.tooltip &&
                        <OverlayTrigger
                            key={this.props.id}
                            placement='right'
                            overlay={
                                <Tooltip id={this.props.id}>
                                    {this.props.tooltip}
                                </Tooltip>
                            }
                        >
                            <span class="form-help ml-5" aria-describedby="tooltip-right">?</span>
                        </OverlayTrigger>
                    }
                </label>
            </>
        );
    }
}

LabelToolTip.propTypes = {
    title: PropTypes.string,
    tooltip: PropTypes.string,
    id: PropTypes.string,
    className: PropTypes.string
}
export default LabelToolTip;