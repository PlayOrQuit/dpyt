import React from 'react';

class MenuDropdown extends React.Component{
    render() {
        return(
            <div className="item-action dropdown">
                <a href="javascript:void(0)" data-toggle="dropdown" className="icon" aria-expanded="false">
                    <i className="fe fe-more-vertical"></i>
                </a>
                <div className="dropdown-menu dropdown-menu-right dropdown-custom" x-placement="bottom-end">
                    <a href="javascript:void(0)" className="dropdown-item"><i
                        className="dropdown-icon fe fe-tag"></i> Action </a>
                    <a href="javascript:void(0)" className="dropdown-item"><i
                        className="dropdown-icon fe fe-edit-2"></i> Another action </a>
                    <a href="javascript:void(0)" className="dropdown-item"><i
                        className="dropdown-icon fe fe-message-square"></i> Something else here</a>
                    <div className="dropdown-divider"></div>
                    <a href="javascript:void(0)" className="dropdown-item"><i
                        className="dropdown-icon fe fe-link"></i> Separated link</a>
                </div>
            </div>
        );
    }
}